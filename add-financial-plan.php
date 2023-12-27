<?php
require('includes/head.php');
if ($permission) {
   try {
      if (isset($_GET['proj'])) {
         $encoded_projid = $_GET['proj'];
         $decode_projid = base64_decode($encoded_projid);
         $projid_array = explode("encodefnprj", $decode_projid);
         $projid = $projid_array[1];
         $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
         $query_rsProjects->execute(array(":projid" => $projid));
         $row_rsProjects = $query_rsProjects->fetch();
         $totalRows_rsProjects = $query_rsProjects->rowCount();

         $approve_details = "";
         if ($totalRows_rsProjects > 0) {
            $implimentation_type = $row_rsProjects['projcategory'];
            $projname = $row_rsProjects['projname'];
            $projcode = $row_rsProjects['projcode'];
            $projcost = $row_rsProjects['projcost'];
            $projfscyear = $row_rsProjects['projfscyear'];
            $projduration = $row_rsProjects['projduration'];
            $mne_cost = $row_rsProjects['mne_budget'];
            $direct_cost = $row_rsProjects['direct_cost'];
            $administrative_cost = $row_rsProjects['administrative_cost'];
            $implementation_cost = $projcost - $mne_cost;
            $progid = $row_rsProjects['progid'];
            $projstartdate = $row_rsProjects['projstartdate'];
            $projenddate = $row_rsProjects['projenddate'];
            $project_sub_stage = $row_rsProjects['proj_substage'];
            $workflow_stage = $row_rsProjects['projstage'];
            $project_directorate = $row_rsProjects['directorate'];

            $projstartyear = date('Y', strtotime($projstartdate));
            $end_year = date('Y', strtotime($projenddate));

            $years = ($end_year - $projstartyear) + 1;

            $query_rsYear =  $db->prepare("SELECT * FROM tbl_fiscal_year where id ='$projfscyear'");
            $query_rsYear->execute();
            $row_rsYear = $query_rsYear->fetch();

            $starting_year = $row_rsYear ? $row_rsYear['yr'] : false;
            $s_date = $starting_year . "-07-01";
            $project_end_date = date('Y-m-d', strtotime($s_date . ' + ' . $projduration . ' days'));
            $end_month = date('m', strtotime($project_end_date));
            $end_year_c = date('Y', strtotime($project_end_date));
            $endyear = $end_month >= 7 && $end_month <= 12 ? $end_year_c : $end_year_c - 1;
            $financial_years = ($endyear - $starting_year) + 1;

            $query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
            $query_rsOutputs->execute(array(":projid" => $projid));
            $row_rsOutputs = $query_rsOutputs->fetch();
            $totalRows_rsOutputs = $query_rsOutputs->rowCount();

            $query_rsProjBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid = :projid");
            $query_rsProjBudget->execute(array(":projid" => $projid));
            $row_rsProjBudget = $query_rsProjBudget->fetch();

            // query the
            $query_rsProjFinancier =  $db->prepare("SELECT *, f.financier FROM tbl_myprojfunding m inner join tbl_financiers f ON f.id=m.financier WHERE projid = :projid ORDER BY amountfunding desc");
            $query_rsProjFinancier->execute(array(":projid" => $projid));
            $totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

            $summary = '';
            $summary_budget = 0;
            $output_cost_val = [];
            $total_direct_cost = $total_direct_cost_percentage = $total_administrative_cost  = $total_administrative_cost_percentage = 0;
            $approval_stage = ($project_sub_stage  >= 2) ? true : false;
            $output_remeinder = 0;
?>
            <!-- start body  -->
            <section class="content">
               <div class="container-fluid">
                  <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                     <h4 class="contentheader">
                        <?= $icon ?>
                        <?php echo $pageTitle ?>
                        <div class="btn-group" style="float:right">
                           <div class="btn-group" style="float:right">
                              <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
                                 Go Back
                              </a>
                           </div>
                        </div>
                     </h4>
                  </div>
                  <div class="card">
                     <div class="row clearfix">
                        <div class="block-header">
                           <?= $results; ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="card-header">
                              <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                       <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
                                       <li class="list-group-item"><strong>Project Code: </strong> <?= $projcode ?> </li>
                                       <li class="list-group-item"><strong>Total Project Cost: </strong> Ksh. <?php echo number_format($projcost, 2); ?> </li>
                                       <li class="list-group-item"><strong>Direct Cost: </strong> Ksh. <?= number_format($direct_cost, 2) ?> </li>
                                       <li class="list-group-item"><strong>Project M&E Cost: </strong> Ksh. <?= number_format($mne_cost, 2) ?> </li>
                                       <li class="list-group-item"><strong>Administrative Cost: </strong> Ksh. <?= number_format($administrative_cost, 2) ?> </li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <div class="body">
                              <fieldset class="scheduler-border" style=" border-radius:3px">
                                 <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-file-text" aria-hidden="true"></i> Cost
                                 </legend>
                                 <div class="card-header">
                                    <ul class="nav nav-tabs" style="font-size:14px">
                                       <li class="active">
                                          <a data-toggle="tab" href="#home"><i class="fa fa-hourglass-half bg-orange" aria-hidden="true"></i> Direct Project Cost &nbsp;<span class="badge bg-orange"></span></a>
                                       </li>
                                       <?php
                                       if ($administrative_cost > 0) {
                                       ?>
                                          <li>
                                             <a data-toggle="tab" href="#menu1"><i class="fa fa-pencil-square-o bg-light-blue" aria-hidden="true"></i> Administrative/Operational Cost&nbsp;<span class="badge bg-light-blue"></span></a>
                                          </li>
                                       <?php
                                       }
                                       ?>
                                    </ul>
                                 </div>
                                 <div class="tab-content">
                                    <div id="home" class="tab-pane fade in active">

                                       <div class="body">
                                          <?php
                                          $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
                                          $query_Sites->execute(array(":projid" => $projid));
                                          $rows_sites = $query_Sites->rowCount();
                                          if ($rows_sites > 0) {
                                             $counter = 0;
                                             while ($row_Sites = $query_Sites->fetch()) {
                                                $site_id = $row_Sites['site_id'];
                                                $site = $row_Sites['site'];
                                                $counter++;
                                          ?>
                                                <fieldset class="scheduler-border">
                                                   <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                      <i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $counter ?> : <?= $site ?>
                                                   </legend>
                                                   <?php
                                                   $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
                                                   $query_Site_Output->execute(array(":site_id" => $site_id));
                                                   $rows_Site_Output = $query_Site_Output->rowCount();
                                                   if ($rows_Site_Output > 0) {
                                                      $output_counter = 0;
                                                      while ($row_Site_Output = $query_Site_Output->fetch()) {
                                                         $output_counter++;
                                                         $output_id = $row_Site_Output['outputid'];
                                                         $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                                                         $query_Output->execute(array(":outputid" => $output_id));
                                                         $row_Output = $query_Output->fetch();
                                                         $total_Output = $query_Output->rowCount();
                                                         if ($total_Output) {
                                                            $output_id = $row_Output['id'];
                                                            $output = $row_Output['indicator_name'];
                                                   ?>
                                                            <fieldset class="scheduler-border">
                                                               <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                                  <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $output_counter ?> : <?= $output ?>
                                                               </legend>
                                                               <?php
                                                               $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
                                                               $query_rsMilestone->execute(array(":output_id" => $output_id));
                                                               $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                                               if ($totalRows_rsMilestone > 0) {
                                                                  while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                                                     $milestone = $row_rsMilestone['milestone'];
                                                                     $msid = $row_rsMilestone['msid'];
                                                                     $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1 AND site_id=:site_id AND tasks=:tasks");
                                                                     $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ':site_id' => $site_id, ":tasks" => $msid));
                                                                     $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                                                     $sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
                                                                     $total_cost = 0;
                                                                     $edit = $sum_cost > 0 ? 1 : 0;

                                                                     $budget_line_details =
                                                                        "{
                                                                        cost_type : 1,
                                                                        output_id: $output_id,
                                                                        budget_line_id: 0,
                                                                        budget_line: '',
                                                                        plan_id: 0,
                                                                        task_id:$msid,
                                                                        edit:$edit,
                                                                        site_id:$site_id,
                                                                        sum_cost:$sum_cost
                                                                     }";
                                                               ?>
                                                                     <div class="row clearfix">
                                                                        <input type="hidden" name="task_amount[]" id="task_amount<?= $msid ?>" class="task_costs" value="<?= $sum_cost ?>">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                           <div class="card-header">
                                                                              <div class="row clearfix">
                                                                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <ul class="list-group">
                                                                                       <li class="list-group-item list-group-item list-group-item-action active">Task: <?= $milestone ?>
                                                                                          <div class="btn-group" style="float:right">
                                                                                             <div class="btn-group" style="float:right">
                                                                                                <button type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" onclick="add_budgetline(<?= $budget_line_details ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                                                   <?php echo $edit == 1 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                                                </button>
                                                                                             </div>
                                                                                          </div>
                                                                                       </li>
                                                                                       <li class="list-group-item"><strong>Task Cost: </strong> <?= number_format($sum_cost, 2) ?> </li>
                                                                                    </ul>
                                                                                 </div>
                                                                              </div>
                                                                           </div>
                                                                           <div class="table-responsive">
                                                                              <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                                                 <thead>
                                                                                    <tr>
                                                                                       <th style="width:5%">#</th>
                                                                                       <th style="width:40%">Item </th>
                                                                                       <th style="width:25%">Unit of Measure</th>
                                                                                       <th style="width:10%">No. of Units</th>
                                                                                       <th style="width:10%">Unit Cost (Ksh)</th>
                                                                                       <th style="width:10%">Total Cost (Ksh)</th>
                                                                                    </tr>
                                                                                 </thead>
                                                                                 <tbody>
                                                                                    <?php
                                                                                    if ($edit == 1) {
                                                                                       $query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id ");
                                                                                       $query_rsOther_cost_plan->execute(array(":task_id" => $msid, ':site_id' => $site_id));
                                                                                       $totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
                                                                                       if ($totalRows_rsOther_cost_plan > 0) {
                                                                                          $table_counter = 0;
                                                                                          while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch()) {
                                                                                             $table_counter++;
                                                                                             $rmkid = $row_rsOther_cost_plan['id'];
                                                                                             $description = $row_rsOther_cost_plan['description'];
                                                                                             $financial_year = $row_rsOther_cost_plan['financial_year'];
                                                                                             $unit = $row_rsOther_cost_plan['unit'];
                                                                                             $unit_cost = $row_rsOther_cost_plan['unit_cost'];
                                                                                             $units_no = $row_rsOther_cost_plan['units_no'];
                                                                                             $total_cost = $unit_cost * $units_no;

                                                                                             $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                                             $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                                             $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                                             $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                             $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
                                                                                    ?>
                                                                                             <tr id="row">
                                                                                                <td style="width:5%"><?= $table_counter ?></td>
                                                                                                <td style="width:40%"><?= $description ?></td>
                                                                                                <td style="width:25%"><?= $unit_of_measure ?></td>
                                                                                                <td style="width:10%"><?= number_format($units_no) ?></td>
                                                                                                <td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
                                                                                                <td style="width:10%"><?= number_format($total_cost, 2) ?></td>
                                                                                             </tr>
                                                                                          <?php
                                                                                          }
                                                                                       }
                                                                                    } else {
                                                                                       $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
                                                                                       $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
                                                                                       $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                                                       if ($totalRows_rsTasks > 0) {
                                                                                          $tcounter = 0;
                                                                                          while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                                             $tcounter++;
                                                                                             $task_name = $row_rsTasks['task'];
                                                                                             $task_id = $row_rsTasks['tkid'];
                                                                                             $unit =  $row_rsTasks['unit_of_measure'];
                                                                                             $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id");
                                                                                             $query_rsTask_parameters->execute(array(":task_id" => $msid, ':site_id' => $site_id));
                                                                                             $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
                                                                                             $row_rsTask_parameters = $query_rsTask_parameters->fetch();
                                                                                             $unit_cost = $units_no = 0;
                                                                                             if ($totalRows_rsTask_parameters > 0) {
                                                                                                $unit_cost = $row_rsTask_parameters['unit_cost'];
                                                                                                $units_no =  $row_rsTask_parameters['units_no'];
                                                                                                $total_cost = $unit_cost * $units_no;
                                                                                             }

                                                                                             $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                                             $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                                             $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                                             $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                             $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
                                                                                          ?>
                                                                                             <tr id="row<?= $tcounter ?>">
                                                                                                <td style="width:5%"><?= $tcounter ?></td>
                                                                                                <td style="width:40%"><?= $task_name ?></td>
                                                                                                <td style="width:25%"><?= $unit_of_measure ?></td>
                                                                                                <td style="width:10%"><?= number_format($units_no) ?></td>
                                                                                                <td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
                                                                                                <td style="width:10%"><?= number_format($total_cost, 2) ?></td>
                                                                                             </tr>
                                                                                    <?php
                                                                                          }
                                                                                       }
                                                                                    }
                                                                                    ?>
                                                                                 </tbody>
                                                                              </table>
                                                                           </div>
                                                                        </div>
                                                                     </div>
                                                               <?php
                                                                  }
                                                               }
                                                               ?>
                                                            </fieldset>
                                                   <?php
                                                         }
                                                      }
                                                   }
                                                   ?>
                                                </fieldset>
                                                <?php
                                             }
                                          }

                                          $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE (indicator_mapping_type=2 OR indicator_mapping_type=0) AND projid = :projid");
                                          $query_Output->execute(array(":projid" => $projid));
                                          $total_Output = $query_Output->rowCount();
                                          $outputs = '';
                                          if ($total_Output > 0) {
                                             $outputs = '';
                                             if ($total_Output > 0) {
                                                $counter = 0;

                                                while ($row_rsOutput = $query_Output->fetch()) {
                                                   $output_id = $row_rsOutput['id'];
                                                   $output = $row_rsOutput['indicator_name'];
                                                   $counter++;
                                                ?>
                                                   <fieldset class="scheduler-border">
                                                      <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                         <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
                                                      </legend>
                                                      <?php
                                                      $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
                                                      $query_rsMilestone->execute(array(":output_id" => $output_id));
                                                      $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                                      if ($totalRows_rsMilestone > 0) {
                                                         while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                                            $milestone = $row_rsMilestone['milestone'];
                                                            $msid = $row_rsMilestone['msid'];
                                                            $site_id = 0;
                                                            $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1 AND tasks=:tasks ");
                                                            $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":tasks" => $msid));
                                                            $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                                            $sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
                                                            $total_cost = 0;
                                                            $edit = $sum_cost > 0 ? 1 : 0;

                                                            $budget_line_details =
                                                               "{
                                                               cost_type : 1,
                                                               output_id: $output_id,
                                                               budget_line_id: 0,
                                                               budget_line: '',
                                                               plan_id: 0,
                                                               task_id:$msid,
                                                               edit:$edit,
                                                               site_id:0,
                                                               sum_cost:$sum_cost
                                                            }";
                                                      ?>
                                                            <div class="row clearfix">
                                                               <input type="hidden" name="task_amount[]" id="task_amount<?= $msid ?>" class="task_costs" value="<?= $sum_cost ?>">
                                                               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                  <div class="card-header">
                                                                     <div class="row clearfix">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                           <ul class="list-group">
                                                                              <li class="list-group-item list-group-item list-group-item-action active">Task: <?= $milestone ?>
                                                                                 <div class="btn-group" style="float:right">
                                                                                    <div class="btn-group" style="float:right">
                                                                                       <button type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" onclick="add_budgetline(<?= $budget_line_details ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                                          <?php echo $edit == 1 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                                       </button>
                                                                                    </div>
                                                                                 </div>
                                                                              </li>
                                                                              <li class="list-group-item"><strong>Task Cost: </strong> <?= number_format($sum_cost, 2) ?> </li>
                                                                           </ul>
                                                                        </div>
                                                                     </div>
                                                                  </div>
                                                                  <div class="table-responsive">
                                                                     <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                                        <thead>
                                                                           <tr>
                                                                              <th style="width:5%">#</th>
                                                                              <th style="width:40%">Item</th>
                                                                              <th style="width:25%">Unit of Measure</th>
                                                                              <th style="width:10%">No. of Units</th>
                                                                              <th style="width:10%">Unit Cost (Ksh)</th>
                                                                              <th style="width:10%">Total Cost (Ksh)</th>
                                                                           </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                           <?php
                                                                           if ($edit == 1) {
                                                                              $query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id ");
                                                                              $query_rsOther_cost_plan->execute(array(":task_id" => $msid, ':site_id' => $site_id));
                                                                              $totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
                                                                              if ($totalRows_rsOther_cost_plan > 0) {
                                                                                 $table_counter = 0;
                                                                                 while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch()) {
                                                                                    $table_counter++;
                                                                                    $rmkid = $row_rsOther_cost_plan['id'];
                                                                                    $description = $row_rsOther_cost_plan['description'];
                                                                                    $financial_year = $row_rsOther_cost_plan['financial_year'];
                                                                                    $unit = $row_rsOther_cost_plan['unit'];
                                                                                    $unit_cost = $row_rsOther_cost_plan['unit_cost'];
                                                                                    $units_no = $row_rsOther_cost_plan['units_no'];
                                                                                    $total_cost = $unit_cost * $units_no;

                                                                                    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                                    $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                                    $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                                    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                    $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
                                                                           ?>
                                                                                    <tr id="row">
                                                                                       <td style="width:5%"><?= $table_counter ?></td>
                                                                                       <td style="width:40%"><?= $description ?></td>
                                                                                       <td style="width:25%"><?= $unit_of_measure ?></td>
                                                                                       <td style="width:10%"><?= number_format($units_no) ?></td>
                                                                                       <td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
                                                                                       <td style="width:10%"><?= number_format($total_cost, 2) ?></td>
                                                                                    </tr>
                                                                                 <?php
                                                                                 }
                                                                              }
                                                                           } else {
                                                                              $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
                                                                              $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
                                                                              $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                                              if ($totalRows_rsTasks > 0) {
                                                                                 $tcounter = 0;
                                                                                 while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                                    $tcounter++;
                                                                                    $task_name = $row_rsTasks['task'];
                                                                                    $task_id = $row_rsTasks['tkid'];
                                                                                    $unit =  $row_rsTasks['unit_of_measure'];

                                                                                    $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks=:task_id AND site_id=:site_id");
                                                                                    $query_rsTask_parameters->execute(array(":task_id" => $msid, ':site_id' => $site_id));
                                                                                    $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
                                                                                    $row_rsTask_parameters = $query_rsTask_parameters->fetch();
                                                                                    $unit_cost = $units_no = 0;
                                                                                    if ($totalRows_rsTask_parameters > 0) {
                                                                                       $unit_cost = $row_rsTask_parameters['unit_cost'];
                                                                                       $units_no =  $row_rsTask_parameters['units_no'];
                                                                                       $total_cost = $unit_cost * $units_no;
                                                                                    }

                                                                                    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                                    $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                                    $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                                    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                    $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
                                                                                 ?>
                                                                                    <tr id="row<?= $tcounter ?>">
                                                                                       <td style="width:5%"><?= $tcounter ?></td>
                                                                                       <td style="width:40%"><?= $task_name ?></td>
                                                                                       <td style="width:25%"><?= $unit_of_measure ?></td>
                                                                                       <td style="width:10%"><?= number_format($units_no) ?></td>
                                                                                       <td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
                                                                                       <td style="width:10%"><?= number_format($total_cost, 2) ?></td>
                                                                                    </tr>
                                                                           <?php
                                                                                 }
                                                                              }
                                                                           }
                                                                           ?>
                                                                        </tbody>
                                                                     </table>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                      <?php
                                                         }
                                                      }
                                                      ?>
                                                   </fieldset>
                                          <?php
                                                }
                                             }
                                          }
                                          ?>
                                       </div>
                                    </div>
                                    <?php
                                    if ($administrative_cost > 0) {
                                    ?>
                                       <div id="menu1" class="tab-pane fade">
                                          <?php
                                          $query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type ");
                                          $query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => 2));
                                          $totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
                                          $edit = $totalRows_rsOther_cost_plan > 0 ? 1 : 0;


                                          $cost_type = $budget_line_id = 2;
                                          $query_rsOther_cost_plan1 =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
                                          $query_rsOther_cost_plan1->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
                                          $totalRows_rsOther_cost_plan1 = $query_rsOther_cost_plan1->rowCount();
                                          $row_rsOther_cost_plan1 = $query_rsOther_cost_plan1->fetch();
                                          $plan_id = $totalRows_rsOther_cost_plan1 > 0 ? $row_rsOther_cost_plan1['plan_id'] : 0;


                                          $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
                                          $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
                                          $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                          $totalRows_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->rowCount();
                                          $sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;


                                          $outputid  = $site_id = 0;
                                          $budget_line = "Administrative/Operational Cost";

                                          $budget_line_details =
                                             "{
                                          cost_type : $cost_type,
                                          output_id: $outputid,
                                          budget_line_id: $budget_line_id,
                                          budget_line: '$budget_line',
                                          plan_id: $plan_id,
                                          task_id:0,
                                          edit:$edit,
                                          site_id:$site_id,
                                          sum_cost:$sum_cost
                                       }";
                                          ?>
                                          <div class="header">
                                             <h4 class="contentheader">
                                                Administrative/Operational Cost
                                                <div class="btn-group" style="float:right">
                                                   <div class="btn-group" style="float:right">
                                                      <button type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" onclick="add_budgetline(<?= $budget_line_details ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                         <?php echo $edit == 1 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                      </button>
                                                   </div>
                                                </div>
                                             </h4>
                                          </div>
                                          <div class="body">
                                             <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                   <thead>
                                                      <tr>
                                                         <th style="width:5%">#</th>
                                                         <th style="width:40%">Item</th>
                                                         <th style="width:25%">Unit of Measure</th>
                                                         <th style="width:10%">No. of Units</th>
                                                         <th style="width:10%">Unit Cost (Ksh)</th>
                                                         <th style="width:10%">Total Cost (Ksh)</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody id="budget_lines_table2">
                                                      <?php
                                                      if ($totalRows_rsOther_cost_plan > 0) {
                                                         $table_counter = 0;
                                                         while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch()) {
                                                            $table_counter++;
                                                            $rmkid = $row_rsOther_cost_plan['id'];
                                                            $description = $row_rsOther_cost_plan['description'];
                                                            $financial_year = $row_rsOther_cost_plan['financial_year'];
                                                            $unit = $row_rsOther_cost_plan['unit'];
                                                            $unit_cost = $row_rsOther_cost_plan['unit_cost'];
                                                            $units_no = $row_rsOther_cost_plan['units_no'];
                                                            $total_cost = $unit_cost * $units_no;

                                                            $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                            $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                            $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                            $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                            $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
                                                      ?>
                                                            <tr id="row">
                                                               <td style="width:5%"><?= $table_counter ?></td>
                                                               <td style="width:40%"><?= $description ?></td>
                                                               <td style="width:25%"><?= $unit_of_measure ?></td>
                                                               <td style="width:10%"><?= number_format($units_no) ?></td>
                                                               <td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
                                                               <td style="width:10%"><?= number_format($total_cost, 2) ?></td>
                                                            </tr>
                                                      <?php
                                                         }
                                                      }
                                                      ?>
                                                   </tbody>
                                                </table>
                                             </div>
                                          </div>
                                       </div>
                                    <?php
                                    }
                                    ?>
                                 </div>
                              </fieldset>
                              <fieldset class="scheduler-border" style="border-radius:3px">
                                 <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-file-text" aria-hidden="true"></i> Plan Summary
                                 </legend>
                                 <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="" style="width:100%">
                                       <thead>
                                          <tr>
                                             <th width="5%">#</th>
                                             <th width="40%">Costline</th>
                                             <th width="20%" class="text-right">Budget (Kshs)</th>
                                             <th width="20%" class="text-right">Planned (Kshs)</th>
                                             <th width="15%" class="text-right">% Planned </th>
                                          </tr>
                                       </thead>
                                       <tbody id="">
                                          <?php
                                          $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1");
                                          $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
                                          $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                          $direct_budget = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
                                          $total_direct_cost_percentage = $direct_budget > 0 ? ($direct_budget / $direct_cost) * 100 : 0;

                                          $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
                                          $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => 2));
                                          $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                          $administrative_budget = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
                                          $total_administrative_cost_percentage = $administrative_budget > 0 ? ($administrative_budget / $administrative_cost) * 100 : 0;
                                          ?>
                                          <tr>
                                             <td>1</td>
                                             <td>Project Direct Cost Plan</td>
                                             <td class="text-right"><?= number_format($direct_cost, 2) ?></td>
                                             <td class="text-right"><?= number_format($direct_budget, 2) ?></td>
                                             <td id="perc" class="text-right"><?= number_format($total_direct_cost_percentage, 2) ?> %</td>
                                          </tr>
                                          <tr>
                                             <td>2</td>
                                             <td>Administrative/Operational Cost Plan</td>
                                             <td class="text-right"><?= number_format($administrative_cost, 2) ?></td>
                                             <td class="text-right"><?= number_format($administrative_budget, 2) ?></td>
                                             <td id="perc" class="text-right"><?= number_format($total_administrative_cost_percentage, 2) ?> %</td>
                                          </tr>

                                       </tbody>
                                    </table>
                                 </div>
                                 <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                       <div class="col-md-12 text-center">
                                          <?php
                                          function validate_tasks_cost()
                                          {
                                             global $db, $projid;
                                             $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
                                             $query_Sites->execute(array(":projid" => $projid));
                                             $rows_sites = $query_Sites->rowCount();

                                             $outputs = $tasks = [];
                                             if ($rows_sites > 0) {
                                                while ($row_Sites = $query_Sites->fetch()) {
                                                   $site_id = $row_Sites['site_id'];
                                                   $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
                                                   $query_Site_Output->execute(array(":site_id" => $site_id));
                                                   $rows_Site_Output = $query_Site_Output->rowCount();
                                                   if ($rows_Site_Output > 0) {
                                                      while ($row_Site_Output = $query_Site_Output->fetch()) {
                                                         $output_id = $row_Site_Output['outputid'];
                                                         $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id");
                                                         $query_rsTasks->execute(array(":output_id" => $output_id));
                                                         $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                         if ($totalRows_rsTasks > 0) {
                                                            while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                               $task_id = $row_rsTasks['tkid'];
                                                               $task = $row_rsTasks['task'];
                                                               $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE subtask_id=:subtask_id AND site_id=:site_id");
                                                               $query_rsTask_parameters->execute(array(":subtask_id" => $task_id, ':site_id' => $site_id));
                                                               $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
                                                               $outputs[] = $totalRows_rsTask_parameters > 0 ? true : false;
                                                               $tasks[] = $task . " status => "  .  $totalRows_rsTask_parameters;
                                                            }
                                                         }
                                                      }
                                                   }
                                                }
                                             }

                                             $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE (indicator_mapping_type=2 || indicator_mapping_type=0) AND projid = :projid");
                                             $query_Output->execute(array(":projid" => $projid));
                                             $total_Output = $query_Output->rowCount();
                                             if ($total_Output > 0) {
                                                while ($row_rsOutput = $query_Output->fetch()) {
                                                   $output_id = $row_rsOutput['id'];
                                                   $site_id = 0;
                                                   $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id");
                                                   $query_rsTasks->execute(array(":output_id" => $output_id));
                                                   $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                   if ($totalRows_rsTasks > 0) {
                                                      while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                         $task_id = $row_rsTasks['tkid'];
                                                         $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE subtask_id=:subtask_id AND site_id=:site_id");
                                                         $query_rsTask_parameters->execute(array(":subtask_id" => $task_id, ':site_id' => $site_id));
                                                         $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();
                                                         $outputs[] = $totalRows_rsTask_parameters > 0 ? true : false;
                                                      }
                                                   }
                                                }
                                             }
                                             return !in_array(false, $outputs) ? true : false;
                                          }

                                          function validate_costs()
                                          {
                                             global $db, $projid, $direct_cost, $administrative_cost;
                                             $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1 ");
                                             $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
                                             $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                             $direct_budget = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;

                                             $administrative_budget = 0;
                                             if ($administrative_cost > 0) {
                                                $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type");
                                                $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => 2));
                                                $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                                $administrative_budget = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
                                             }

                                             $result =   $direct_budget == $direct_cost && $administrative_budget == $administrative_cost ? true : false;
                                             return $result;
                                          }

                                          $proceed = validate_tasks_cost() && validate_costs() ? true : false;
                                          if ($proceed) {
                                             $assigned_responsible = check_if_assigned($projid, $workflow_stage, $project_sub_stage, 1);
                                             $stage =  $implimentation_type == 1 ? $workflow_stage + 1 : $workflow_stage;
                                             $approve_details =
                                                "{
                                                   get_edit_details: 'details',
                                                   projid:$projid,
                                                   workflow_stage:$stage,
                                                   project_directorate:$project_directorate,
                                                   project_name:'$projname',
                                                   sub_stage:'$project_sub_stage',
                                                }";
                                             if ($assigned_responsible) {
                                                if ($approval_stage) {
                                          ?>
                                                   <button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Approve</button>
                                                <?php
                                                } else {
                                                   $data_entry_details =
                                                      "{
                                                      get_edit_details: 'details',
                                                      projid:$projid,
                                                      workflow_stage:$workflow_stage,
                                                      project_directorate:$project_directorate,
                                                      project_name:'$projname',
                                                      sub_stage:'$project_sub_stage',
                                                   }";
                                                ?>
                                                   <button type="button" onclick="save_data_entry_project(<?= $data_entry_details ?>)" class="btn btn-success">Proceed</button>
                                          <?php
                                                }
                                             }
                                          }
                                          ?>
                                       </div>
                                    </div>
                                 </form>
                              </fieldset>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>

            <!-- add item -->
            <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
               <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                     <form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
                        <div class="modal-header" style="background-color:#03A9F4">
                           <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Add Other Details</span></h4>
                        </div>
                        <div class="modal-body">
                           <div class="card">
                              <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="add_modal_form">
                                       <fieldset class="scheduler-border">
                                          <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                             <i class="fa fa-calendar" aria-hidden="true"></i> Budgetline Details
                                          </legend>
                                          <div id="budget_line">
                                             <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                   <div class="table-responsive">
                                                      <table class="table table-bordered">
                                                         <thead>
                                                            <tr>
                                                               <th>Item No.</th>
                                                               <th>Description </th>
                                                               <th>Unit of Measure</th>
                                                               <th>No. of Units </th>
                                                               <th>Unit Cost</th>
                                                               <th>Total Cost (Ksh)</th>
                                                               <th style="width:2%">
                                                                  <button type="button" name="addplus" id="addplus_financier" onclick="add_budget_costline();" class="btn btn-success btn-sm">
                                                                     <span class="glyphicon glyphicon-plus">
                                                                     </span>
                                                                  </button>
                                                               </th>
                                                            </tr>
                                                         </thead>
                                                         <tbody id="budget_lines_values_table">
                                                            <tr>
                                                               <td>
                                                                  <input type="text" name="order[]" class="form-control sequence" id="order1">
                                                               </td>
                                                               <td>
                                                                  <input type="text" name="description[]" class="form-control" id="description">
                                                               </td>
                                                               <td>
                                                                  <select name="unit_of_measure[]" id="unit_of_measurerow1" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
                                                                     <?php
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
                                                                     echo $unit_options;
                                                                     ?>
                                                                  </select>
                                                               </td>
                                                               <td>
                                                                  <input type="number" name="unit_cost[]" min="0" class="form-control" onchange="calculate_total_cost(1)" onkeyup="calculate_total_cost(1)" id="unit_cost1">
                                                               </td>
                                                               <td>
                                                                  <input type="number" name="no_units[]" min="0" class="form-control" onchange="calculate_total_cost(1)" onkeyup="calculate_total_cost(1)" id="no_units1">
                                                               </td>
                                                               <td>
                                                                  <input type="hidden" name="subtask_id[]" class="form-control" id="subtask_id1" value="0" />
                                                                  <input type="hidden" name="task_type[]" class="form-control" id="task_type1" value="1" />
                                                                  <input type="hidden" name="subtotal_amount[]" id="subtotal_amount1" class="subtotal_amount subamount" value="">
                                                                  <span id="subtotal_cost1" style="color:red"></span>
                                                               </td>
                                                               <td style="width:2%"></td>
                                                            </tr>
                                                         </tbody>
                                                         <tfoot id="budget_line_foot">
                                                            <tr>
                                                               <td><strong>Balance</strong></td>
                                                               <td>
                                                                  <input type="hidden" name="remaining_balance" id="remaining_balance" value="" />
                                                                  <input type="text" name="remaining_balance1" value="" id="remaining_balance1" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                               </td>
                                                               <td><strong>Sub Total</strong></td>
                                                               <td>
                                                                  <input type="text" name="subtotal_amount" value="" id="psub_total_amount3" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                               </td>
                                                               <td> <strong>% Sub Total</strong></td>
                                                               <td colspan='2'>
                                                                  <input type="text" name="subtotal_percentage" value="%" id="psub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                               </td>
                                                            </tr>
                                                         </tfoot>
                                                      </table>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </fieldset>
                                    </div>
                                 </div>
                              </div>
                           </div> <!-- /modal-body -->
                           <div class="modal-footer">
                              <div class="col-md-12 text-center">
                                 <input type="hidden" name="myprojid" id="myprojid" value="<?= $projid ?>">
                                 <input type="hidden" class="form-control" id="direct_cost" value="<?= $direct_cost ?>">
                                 <input type="hidden" class="form-control" id="administrative_cost" value="<?= $administrative_cost ?>">
                                 <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                 <input type="hidden" name="user_name" id="username" value="<?= $user_name ?>">
                                 <input type="hidden" name="store" id="store" value="new">
                                 <input type="hidden" name="budget_line_id" id="budget_line_id" value="">
                                 <input type="hidden" name="cost_type" id="cost_type" value="">
                                 <input type="hidden" name="output_id" id="output_id" value="">
                                 <input type="hidden" name="plan_id" id="plan_id" value="">
                                 <input type="hidden" name="site_id" id="site_id" value="">
                                 <input type="hidden" name="task_id" id="task_id" value="">
                                 <button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value=""> Save </button>
                                 <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                              </div>
                           </div> <!-- /modal-footer -->
                     </form> <!-- /.form -->
                  </div> <!-- /modal-content -->
               </div> <!-- /modal-dailog -->
            </div>
            <!-- End add item -->
<?php
         } else {
            $results =  restriction();
            echo $results;
         }
      } else {
         $results =  restriction();
         echo $results;
      }
   } catch (PDOException $ex) {
      $results = flashMessage("An error occurred: " . $ex->getMessage());
   }
} else {
   $results =  restriction();
   echo $results;
}

require('includes/footer.php');
?>

<script>
   const redirect_url = "add-project-financial-plan";
</script>
<script src="assets/js/financialplan/index.js"></script>
<script src="assets/js/master/index.js"></script>