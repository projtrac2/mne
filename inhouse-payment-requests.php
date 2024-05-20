<?php
try {
   require('includes/head.php');
   if ($permission) {
?>
      <!-- start body  -->
      <section class="content">
         <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
               <h4 class="contentheader">
                  <?= $icon . "  " . $pageTitle ?>
                  <div class="btn-group" style="float:right">
                     <div class="btn-group" style="float:right">
                     </div>
                  </div>
               </h4>
            </div>
            <div class="row clearfix">
               <div class="block-header">
                  <?= $results; ?>
                  <div class="header" style="padding-bottom:0px">
                     <div class="button-demo" style="margin-top:-15px">
                        <span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
                        <a href="contractor-payment-requests.php" class="btn bg-light-blue waves-effect" style="margin-top:10px">Contractor</a>
                        <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">In House</a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="card">
                     <div class="card-header">
                        <ul class="nav nav-tabs" style="font-size:14px">
                           <li class="active">
                              <a data-toggle="tab" href="#home">
                                 <i class="fa fa-caret-square-o-down bg-purple" aria-hidden="true"></i>
                                 New Request&nbsp;
                                 <span class="badge bg-purple"></span>
                              </a>
                           </li>
                           <li>
                              <a data-toggle="tab" href="#menu1">
                                 <i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
                                 Pending Requests &nbsp;
                                 <span class="badge bg-deep-purple"></span>
                              </a>
                           </li>
                           <li>
                              <a data-toggle="tab" href="#menu2">
                                 <i class="fa fa-caret-square-o-right bg-indigo" aria-hidden="true"></i>
                                 Paid Requests &nbsp;
                                 <span class="badge bg-indigo"></span>
                              </a>
                           </li>
                        </ul>
                     </div>
                     <div class="body">
                        <!-- ============================================================== -->
                        <!-- Start Page Content -->
                        <!-- ============================================================== -->
                        <div class="tab-content">
                           <div id="home" class="tab-pane fade in active">
                              <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                 <h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#9C27B0"></i> Make a New Request</h4>
                              </div>
                              <div class="table-responsive">
                                 <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                       <tr class="bg-purple">
                                          <th style="width:5%">#</th>
                                          <th style="width:70%">Project Name</th>
                                          <th style="width:15%">Project Category</th>
                                          <th style="width:10%">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       $query_rsprojects =  $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage > 7 AND (p.projstatus=3 OR p.projstatus=4 OR p.projstatus=11 OR projstatus=5) AND administrative_cost > 0");
                                       $query_rsprojects->execute();
                                       $total_rsprojects = $query_rsprojects->rowCount();
                                       if ($total_rsprojects > 0) {
                                          $counter = 0;
                                          while ($rows_rsprojects = $query_rsprojects->fetch()) {
                                             $projid = $rows_rsprojects['projid'];
                                             $progid = $rows_rsprojects['progid'];
                                             $project_name = $rows_rsprojects['projname'];
                                             $project_department = $rows_rsprojects['projsector'];
                                             $project_section = $rows_rsprojects['projdept'];
                                             $project_directorate = $rows_rsprojects['directorate'];
                                             $projcategory = $rows_rsprojects['projcategory'];
                                             $implementation_type = $projcategory == 2 ? 'Contractor' : 'Inhouse';
                                             $projid_hashed = base64_encode("projid54321{$projid}");

                                             $filter_department = view_record($project_department, $project_section, $project_directorate);
                                             if ($filter_department) {
                                                $counter++;
                                       ?>
                                                <tr class="">
                                                   <td><?= $counter ?></td>
                                                   <td><?= $project_name ?></td>
                                                   <td><?= $implementation_type ?></td>
                                                   <td>
                                                      <a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#addFormModal" onclick="add_request_details(<?= $projid ?>,<?= $projcategory ?>)" class="btn bg-purple waves-effect" title="Click here to request payment" style="height:25px; padding-top:0px">
                                                         <i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Request
                                                      </a>
                                                   </td>
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
                           <div id="menu1" class="tab-pane">
                              <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                 <h4 style="width:100%"><i class="fa fa-hourglass-half fa-sm" style="font-size:25px;color:#6c0eb0"></i> New Requests</h4>
                              </div>
                              <div class="table-responsive">
                                 <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                       <tr class="bg-deep-purple">
                                          <th style="width:5%">#</th>
                                          <th style="width:35%">Project Name</th>
                                          <th style="width:10%">Requested Amount</th>
                                          <th style="width:10%">Date Requested</th>
                                          <th style="width:10%">Due Date</th>
                                          <th style="width:10%">Stage</th>
                                          <th style="width:10%">Status</th>
                                          <th style="width:10%">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       $query_rsPayement_reuests =  $db->prepare("SELECT * FROM  tbl_payments_request WHERE status <> 3");
                                       $query_rsPayement_reuests->execute();
                                       $total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
                                       if ($total_rsPayement_reuests > 0) {
                                          $counter = 0;
                                          while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch()) {
                                             $projid = $rows_rsPayement_reuests['projid'];
                                             $request_id = $rows_rsPayement_reuests['id'];
                                             $request_id_hashed = base64_encode("projid54321{$request_id}");
                                             $payment_requested_date = $rows_rsPayement_reuests['date_requested'];
                                             $payment_due_date = $rows_rsPayement_reuests['due_date'];
                                             $payment_status = $rows_rsPayement_reuests['status'];
                                             $payment_stage = $rows_rsPayement_reuests['stage'];
                                             $purpose = $rows_rsPayement_reuests['purpose'];

                                             $query_rsPayment_request_details =  $db->prepare("SELECT SUM(unit_cost * no_of_units) as amount_paid FROM tbl_payments_request_details WHERE request_id=:request_id");
                                             $query_rsPayment_request_details->execute(array(":request_id" => $request_id));
                                             $rows_rsPayment_request_details = $query_rsPayment_request_details->fetch();
                                             $amount_paid = !is_null($rows_rsPayment_request_details['amount_paid']) ? $rows_rsPayment_request_details['amount_paid'] : 0;

                                             $status = $stage = "";
                                             if ($payment_stage == 0) {
                                                $status  =  "Draft";
                                                $stage = "Request";
                                             } else if ($payment_stage == 1) {
                                                $status  = $payment_status == 1 ? "Pending" : "Rejected";
                                                $stage = "CO Department";
                                             } else if ($payment_stage == 2) {
                                                $status  = $payment_status == 1 ? "Pending" : "Rejected";
                                                $stage = "CO Finance";
                                             } else if ($payment_stage == 3) {
                                                $status  = "Pending";
                                                $stage = "Director Finance";
                                             }

                                             $query_rsprojects =  $db->prepare("SELECT * FROM  tbl_projects WHERE projid = :projid");
                                             $query_rsprojects->execute(array(":projid" => $projid));
                                             $rows_rsprojects = $query_rsprojects->fetch();
                                             $total_rsprojects = $query_rsprojects->rowCount();

                                             $progid = $rows_rsprojects['progid'];
                                             $project_name = $rows_rsprojects['projname'];
                                             $projcategory = $rows_rsprojects['projcategory'];

                                             $project_details = "{
                                             projid: $projid,
                                             projcategory: $projcategory,
                                             category: $projcategory,
                                             request_id: $request_id,
                                             purpose:$purpose
                                          }";


                                             $counter++;
                                       ?>
                                             <tr class="">
                                                <td style="width:5%"><?= $counter ?></td>
                                                <td style="width:35%"><?= $project_name ?></td>
                                                <td style="width:10%"><?= number_format($amount_paid, 2) ?></td>
                                                <td style="width:10%"><?= date("Y-m-d", strtotime($payment_requested_date)) ?></td>
                                                <td style="width:10%"><?= date("Y-m-d", strtotime($payment_due_date)) ?></td>
                                                <td style="width:10%"><?= $stage ?></td>
                                                <td style="width:10%"><?= $status ?></td>
                                                <td style="width:10%">
                                                   <!-- Single button -->
                                                   <div class="btn-group">
                                                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                         Options <span class="caret"></span>
                                                      </button>
                                                      <ul class="dropdown-menu">
                                                         <?php
                                                         if ($payment_status == 2 || $payment_status == 0) {
                                                         ?>
                                                            <li>
                                                               <a type="button" href="inhouse-payment-request-amend.php?request_id=<?= $request_id_hashed ?>" title="Click here to amend request payment">
                                                                  <i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Amend
                                                               </a>
                                                            </li>
                                                         <?php
                                                         }
                                                         ?>
                                                         <li>
                                                            <a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="get_more_info(<?= $request_id ?>)">
                                                               <i class="fa fa-info"></i>More Info
                                                            </a>
                                                         </li>
                                                      </ul>
                                                   </div>
                                                </td>
                                             </tr>
                                       <?php

                                          }
                                       }
                                       ?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div id="menu2" class="tab-pane">
                              <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                 <h4 style="width:100%"><i class="fa fa-money" style="font-size:25px;color:indigo"></i> Paid Requests</h4>
                              </div>
                              <div class="table-responsive">
                                 <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                       <tr class="bg-indigo">
                                          <th style="width:5%">#</th>
                                          <th style="width:45%">Project Name</th>
                                          <th style="width:10%">Amount Paid</th>
                                          <th style="width:10%">Date Requested</th>
                                          <th style="width:10%">Paid By</th>
                                          <th style="width:10%">Date Paid</th>
                                          <th style="width:10%">Other Details</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       $query_rsPayement_reuests =  $db->prepare("SELECT r.*, d.created_at, d.created_by, d.date_paid, d.receipt  FROM tbl_payments_request r INNER JOIN tbl_payments_disbursed d ON d.request_id = r.id WHERE status = 3 AND request_type=1");
                                       $query_rsPayement_reuests->execute();
                                       $total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
                                       if ($total_rsPayement_reuests > 0) {
                                          $counter = 0;
                                          while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch()) {
                                             $costline_id = $rows_rsPayement_reuests['id'];
                                             $projid = $rows_rsPayement_reuests['projid'];
                                             $request_id = $rows_rsPayement_reuests['id'];
                                             $date_requested = $rows_rsPayement_reuests['date_requested'];
                                             $date_paid = $rows_rsPayement_reuests['date_paid'];
                                             $created_by = $rows_rsPayement_reuests['created_by'];
                                             $created_at = $rows_rsPayement_reuests['created_at'];
                                             $receipt = $rows_rsPayement_reuests['receipt'];

                                             $query_rsPayment_request_details =  $db->prepare("SELECT SUM(unit_cost * no_of_units) as amount_paid FROM tbl_payments_request_details WHERE request_id=:request_id");
                                             $query_rsPayment_request_details->execute(array(":request_id" => $request_id));
                                             $rows_rsPayment_request_details = $query_rsPayment_request_details->fetch();
                                             $amount_paid = $rows_rsPayment_request_details['amount_paid'];

                                             $get_user = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id");
                                             $get_user->execute(array(":user_id" => $created_by));
                                             $count_user = $get_user->rowCount();
                                             $user = $get_user->fetch();
                                             $officer = $user['fullname'];

                                             $query_rsprojects =  $db->prepare("SELECT * FROM  tbl_projects WHERE projid = :projid");
                                             $query_rsprojects->execute(array('projid' => $projid));
                                             $rows_rsprojects = $query_rsprojects->fetch();
                                             $total_rsprojects = $query_rsprojects->rowCount();

                                             $progid = $rows_rsprojects['progid'];
                                             $project_name = $rows_rsprojects['projname'];
                                             $counter++;
                                       ?>
                                             <tr class="">
                                                <td style="width:5%"><?= $counter ?></td>
                                                <td style="width:45%"><?= $project_name ?></td>
                                                <td style="width:10%"><?= number_format($amount_paid, 2) ?></td>
                                                <td style="width:10%"><?= date("Y-m-d", strtotime($date_requested)) ?></td>
                                                <td style="width:10%"><?= $officer ?></td>
                                                <td style="width:10%"><?= date("Y-m-d", strtotime($date_paid)) ?></td>
                                                <td style="width:10%">
                                                   <div class="btn-group">
                                                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                         Options <span class="caret"></span>
                                                      </button>
                                                      <ul class="dropdown-menu">
                                                         <li>
                                                            <a type="button" href="<?= $receipt ?>" target="_blank">
                                                               <i class="fa fa-info"></i> Receipt
                                                            </a>
                                                         </li>
                                                         <li>
                                                            <a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="get_more_info(<?= $costline_id ?>)">
                                                               <i class="fa fa-info"></i>More Info
                                                            </a>
                                                         </li>
                                                      </ul>
                                                   </div>
                                                </td>
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
                        <!-- ============================================================== -->
                        <!-- End PAge Content -->
                        <!-- ============================================================== -->
                     </div>
                  </div>
               </div>
            </div>
      </section>
      <!-- end body  -->


      <!-- add item -->
      <div class="modal fade" id="moreItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header" style="background-color:#03A9F4">
                  <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Payment Request Details</span></h4>
               </div>
               <div class="modal-body">
                  <div class="card">
                     <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="body">
                              <fieldset class="scheduler-border">
                                 <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-calendar" aria-hidden="true"></i> Request Details
                                 </legend>
                                 <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                       <ul class="list-group">
                                          <li class="list-group-item list-group-item list-group-item-action active">Project Name: <span id="project_name"></span> </li>
                                       </ul>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                       <ul class="list-group">
                                          <li class="list-group-item"><strong>Project Code: </strong> <span id="project_code"></span> </li>
                                          <li class="list-group-item"><strong>Requested Amount: </strong> Ksh. <span id="requested_amount"></span> </li>
                                          <li class="list-group-item"><strong>Requested By: </strong> <span id="requested_by"></span> </li>
                                          <li class="list-group-item"><strong>Date Requested: </strong> <span id="date_requested"></span> </li>
                                       </ul>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                       <ul class="list-group">
                                          <li class="list-group-item"><strong>Due Date: </strong> <span id="due_date"></span> </li>
                                          <li class="list-group-item"><strong>Stage: </strong> </strong> <span id="stage"></span> </li>
                                          <li class="list-group-item"><strong>Status: </strong> </strong> <span id="status"></span> </li>
                                          <li class="list-group-item"><strong>Purpose: </strong> </strong> <span id="purpose"></span> </li>
                                       </ul>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 disbursed_div">
                                       <ul class="list-group">
                                          <li class="list-group-item"><strong>Payment Mode: </strong> <span id="payment_mode"></span> </li>
                                          <li class="list-group-item"><strong>Receipt Number: </strong> </strong> <span id="receipt_no"></span> </li>
                                          <li class="list-group-item"><strong>Receipt: </strong> </strong> <a href="" id="receipt" target="blank"><i class="fa fa-download"></i> Download</a> </li>
                                          <li class="list-group-item"><strong>Date Paid: </strong> </strong> <span id="date_paid"></span> </li>
                                       </ul>
                                    </div>
                                 </div>
                                 <div id="direct_cost_div">

                                 </div>
                                 <div class="row clearfix" style="margin-top:5px; margin-bottom:5px" id="costlines_div">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                       <div class="table-responsive">
                                          <table class="table table-bordered">
                                             <input type="hidden" name="task_id[]" id="task_id" value="0">
                                             <thead>
                                                <tr>
                                                   <th style="width:35%">Description </th>
                                                   <th style="width:40%">No. of Units</th>
                                                   <th style="width:15%">Unit Cost</th>
                                                   <th style="width:10%" align="right">Total Cost</th>
                                                </tr>
                                             </thead>
                                             <tbody id="tasks_table">
                                                <tr></tr>
                                                <tr id="_removeTr" class="text-center">
                                                   <td colspan="5">Add Budgetline Costlines</td>
                                                </tr>
                                             </tbody>
                                             <tfoot>
                                                <tr>
                                                   <td colspan="3" align="right"><strong>Sub Total</strong></td>
                                                   <td colspan="1" id="subtotal" align="right"> </td>
                                                </tr>
                                             </tfoot>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </fieldset>
                              <fieldset class="scheduler-border disbursed_div">
                                 <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-comment" aria-hidden="true"></i> Financiers
                                 </legend>
                                 <div id="comment_section">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                       <div class="table-responsive">
                                          <table class="table table-bordered table-striped table-hover" id="financier_table" style="width:100%">
                                             <thead>
                                                <tr>
                                                   <th width="10%">#</th>
                                                   <th width="30%" colspan="3">Financier</th>
                                                   <th width="30%">Ceiling</th>
                                                   <th width="30%">Amount</th>
                                                   <th width="5%">
                                                      <button type="button" name="addplus" id="addplus_financier" onclick="add_row_financier();" class="btn btn-success btn-sm">
                                                         <span class="glyphicon glyphicon-plus">
                                                         </span>
                                                      </button>
                                                   </th>
                                                </tr>
                                             </thead>
                                             <tbody id="financier_table_body">
                                                <tr></tr>
                                                <tr id="removeTr">
                                                   <td colspan="7">Add Financiers</td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </fieldset>
                              <fieldset class="scheduler-border">
                                 <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-comment" aria-hidden="true"></i> Remarks
                                 </legend>
                                 <div id="comments_div">

                                 </div>
                              </fieldset>
                           </div>
                        </div>
                     </div>
                  </div> <!-- /modal-body -->
                  <div class="modal-footer">
                     <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                     </div>
                  </div> <!-- /modal-footer -->
               </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
         </div>
      </div>
      <!-- End add item -->

      <!-- add item -->
      <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <form class="form-horizontal" id="modal_form_submit1" action="" method="POST" enctype="multipart/form-data">
                  <?= csrf_token_html(); ?>
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
                                    <div class="row clearfix">
                                       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                          <label for="purpose" class="control-label">Purpose *:</label>
                                          <div class="form-line">
                                             <select name="purpose" id="purpose1" onchange="check_cost_type()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false">
                                                <option value="">.... Select from list ....</option>
                                                <option value="1">Direct Cost</option>
                                                <option value="2">Administrative/Operational Cost</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                          <label class="control-label">Due Date <span></span>*:</label>
                                          <div class="form-input">
                                             <input type="date" name="due_date" value="" id="due_date" class="form-control" required="required">
                                          </div>
                                       </div>
                                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="budgetline_div">
                                          <div class="table-responsive">
                                             <table class="table table-bordered">
                                                <input type="hidden" name="task_id[]" id="task_id" value="0">
                                                <thead>
                                                   <tr>
                                                      <th style="width:30%">Description </th>
                                                      <th style="width:15%">Unit</th>
                                                      <th style="width:15%">Unit Cost</th>
                                                      <th style="width:10%">No. of Units</th>
                                                      <th style="width:10%">Remaining Units</th>
                                                      <th style="width:15%">Total Cost</th>
                                                      <th style="width:5%">
                                                         <button type="button" name="addplus" id="addplus_financier" onclick="add_budget_costline(0);" class="btn btn-success btn-sm">
                                                            <span class="glyphicon glyphicon-plus">
                                                            </span>
                                                         </button>
                                                      </th>
                                                   </tr>
                                                </thead>
                                                <tbody id="_budget_lines_values_table">
                                                   <tr></tr>
                                                   <tr id="e_removeTr" class="text-center">
                                                      <td colspan="5">Add Budgetline Costlines</td>
                                                   </tr>
                                                </tbody>
                                                <tfoot id="budget_line_foot">
                                                   <tr>
                                                      <td colspan="5"><strong>Sub Total</strong></td>
                                                      <td>
                                                         <input type="text" name="subtotal_amount1" value="" id="sub_total_amount" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                      </td>
                                                   </tr>
                                                </tfoot>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                                 <fieldset class="scheduler-border" id="project_commets_div">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                       <i class="fa fa-comment" aria-hidden="true"></i> Remarks
                                    </legend>
                                    <div id="comment_section">
                                       <div class="col-md-12">
                                          <label class="control-label">Remarks *:</label>
                                          <br />
                                          <div class="form-line">
                                             <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                     </div> <!-- /modal-body -->
                     <div class="modal-footer">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                           <input type="hidden" name="projid" id="project_id" value="">
                           <input type="hidden" name="amount_requested" id="amount_requested" value="">
                           <input type="hidden" name="request_id" id="request_id" value="">
                           <input type="hidden" name="cost_type" id="cost_type" value="">
                           <input type="hidden" name="implementation_type" id="implementation_type" value="">
                           <input type="hidden" name="store" id="store" value="new">
                           <button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value="">
                              Save
                           </button>
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
   require('includes/footer.php');
} catch (PDOException $ex) {
   customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/payment/inhouse.js"></script>