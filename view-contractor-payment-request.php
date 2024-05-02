<?php
try {
    require('includes/head.php');
    if ($permission) {
?>
        <style>
            .modal-lg {
                max-width: 100% !important;
                width: 90%;
            }
        </style>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?>
                        <?= $pageTitle ?>
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
                                <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">Contractor</a>
                                <a href="view-inhouse-payment-requests.php" class="btn bg-light-blue waves-effect" style="margin-top:10px">In House</a>
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
                                                        <th style="width:15%">Project Plan</th>
                                                        <th style="width:10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query_rsprojects =  $db->prepare("SELECT * FROM  tbl_projects WHERE projstage= 10 AND (projstatus <> 2 AND projstatus <> 5 AND projstatus <> 6) ");
                                                    $query_rsprojects->execute();
                                                    $rows_rsprojects = $query_rsprojects->fetch();
                                                    $total_rsprojects = $query_rsprojects->rowCount();

                                                    function  milestone($projid, $paymentstatus)
                                                    {
                                                        global $db;
                                                        $query_rs_payment = $db->prepare("SELECT * FROM tbl_project_payment_plan_details WHERE projid = :projid AND paymentstatus=:paymentstatus ORDER BY id ASC LIMIT 1");
                                                        $query_rs_payment->execute(array(":projid" => $projid, ":paymentstatus" => $paymentstatus));
                                                        $row_rs_payment = $query_rs_payment->fetch();
                                                        $totalRows_rs_payment = $query_rs_payment->rowCount();
                                                    }

                                                    function tasks($projid, $paymentstatus)
                                                    {
                                                        global $db;
                                                        $query_rsPrograms = $db->prepare("SELECT * FROM tbl_task WHERE projid = :projid AND paymentstatus=:paymentstatus");
                                                        $query_rsPrograms->execute(array(":projid" => $projid, ":paymentstatus" => $paymentstatus));
                                                        $row_rsPrograms = $query_rsPrograms->fetch();
                                                        $totalRows_rsPrograms = $query_rsPrograms->rowCount();
                                                        return $totalRows_rsPrograms > 0 ? true : false;
                                                    }

                                                    function project_percentage($projid, $paymentstatus)
                                                    {
                                                        global $db;
                                                        $query_rs_payment = $db->prepare("SELECT * FROM tbl_project_payment_plan_details WHERE projid = :projid AND payment_status=:paymentstatus ORDER BY id ASC LIMIT 1");
                                                        $query_rs_payment->execute(array(":projid" => $projid, ":paymentstatus" => $paymentstatus));
                                                        $row_rs_payment = $query_rs_payment->fetch();
                                                        $totalRows_rs_payment = $query_rs_payment->rowCount();

                                                        $msg = false;
                                                        if ($totalRows_rs_payment > 0) {
                                                            $msid = $row_rs_payment['milestone_id'];
                                                            $id = $row_rs_payment['id'];
                                                            $query_milestone = $db->prepare("SELECT * FROM tbl_milestone WHERE msid=:msid  AND status=5");
                                                            $query_milestone->execute(array(":msid" => $msid));
                                                            $total_milestone = $query_milestone->rowCount();

                                                            $query_rsPayement_reuests =  $db->prepare("SELECT * FROM  tbl_contractor_payment_requests WHERE project_plan=:project_plan");
                                                            $query_rsPayement_reuests->execute(array(":project_plan" => $id));
                                                            $total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();

                                                            $msg = $total_milestone > 0 && $total_rsPayement_reuests == 0 ? true : false;
                                                        }
                                                        return $msg;
                                                    }


                                                    if ($total_rsprojects > 0) {
                                                        $counter = 0;
                                                        do {
                                                            $projid = $rows_rsprojects['projid'];
                                                            $progid = $rows_rsprojects['progid'];
                                                            $project_name = $rows_rsprojects['projname'];
                                                            $projstage = $rows_rsprojects['projstage'];
                                                            $payment_plan = $rows_rsprojects['payment_plan'];
                                                            $contrid = $rows_rsprojects['projcontractor'];
                                                            $query_rsStage =  $db->prepare("SELECT * FROM  tbl_project_workflow_stage WHERE id=:id");
                                                            $query_rsStage->execute(array(":id" => $projstage));
                                                            $rows_rsStage = $query_rsStage->fetch();
                                                            $total_rsStage = $query_rsStage->rowCount();
                                                            $stage = $total_rsStage > 0 ? $rows_rsStage['stage'] : "";

                                                            $query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
                                                            $query_rsPrograms->execute(array(":progid" => $progid));
                                                            $row_rsPrograms = $query_rsPrograms->fetch();
                                                            $totalRows_rsPrograms = $query_rsPrograms->rowCount();

                                                            $project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
                                                            $project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
                                                            $project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";
                                                            $filter_department = $permissions->filter_department_list($project_department, $project_section, $project_directorate);
                                                            // contractor_name contract_no
                                                            $query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = :projid");
                                                            $query_rsTender->execute(array(":projid" => $projid));
                                                            $row_rsTender = $query_rsTender->fetch();
                                                            $totalRows_rsTender = $query_rsTender->rowCount();
                                                            $contract_no = $totalRows_rsTender > 0 ? $row_rsTender['contractrefno'] : '';

                                                            $query_rsContractor = $db->prepare("SELECT * FROM tbl_contractor WHERE contrid = :contrid");
                                                            $query_rsContractor->execute(array(":contrid" => $contrid));
                                                            $row_rsContractor = $query_rsContractor->fetch();
                                                            $totalRows_rsContractor = $query_rsContractor->rowCount();
                                                            $contractor_name = $totalRows_rsContractor > 0 ? $row_rsContractor['contractor_name'] : '';

                                                            $project_details = "{
                                                            project_name:'$project_name',
                                                            projid: $projid,
                                                            payment_plan: $payment_plan,
                                                            request_id: '',
                                                            contractor_name:'$contractor_name',
                                                            contract_no:'$contract_no',
                                                        }";

                                                            $ready_for_payment = false;
                                                            $payment_plan_name = "";
                                                            if ($payment_plan == 1) {
                                                                $ready_for_payment = milestone($projid, 0);
                                                                $payment_plan_name = "Milestone";
                                                            } else if ($payment_plan == 2) {
                                                                $ready_for_payment = tasks($projid, 0);
                                                                $payment_plan_name = "Task";
                                                            } else if ($payment_plan == 3) {
                                                                $ready_for_payment = project_percentage($projid, 0);
                                                                $payment_plan_name = "Project Percentage";
                                                            }

                                                            if ($filter_department && $ready_for_payment) {
                                                                $counter++;
                                                    ?>
                                                                <tr class="">
                                                                    <td><?= $counter ?></td>
                                                                    <td><?= $project_name ?></td>
                                                                    <td><?= ucfirst($payment_plan_name) ?></td>
                                                                    <td>
                                                                        <a type="button" class="btn bg-purple waves-effect" onclick="get_details(<?= $project_details ?>)" data-toggle="modal" id="addFormModalbtn" data-target="#addFormModal" title="Click here to request payment" style="height:25px; padding-top:0px"><i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Request</a>
                                                                    </td>
                                                                </tr>
                                                    <?php
                                                            }
                                                        } while ($rows_rsprojects = $query_rsprojects->fetch());
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
                                                        <th style="width:10%">Payment Plan</th>
                                                        <th style="width:10%">Stage</th>
                                                        <th style="width:10%">Status</th>
                                                        <th style="width:10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query_rsPayement_reuests =  $db->prepare("SELECT * FROM  tbl_contractor_payment_requests WHERE status <> 3");
                                                    $query_rsPayement_reuests->execute();
                                                    $rows_rsPayement_reuests = $query_rsPayement_reuests->fetch();
                                                    $total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();

                                                    if ($total_rsPayement_reuests > 0) {
                                                        $counter = 0;
                                                        do {
                                                            $costline_id = $rows_rsPayement_reuests['id'];
                                                            $projid = $rows_rsPayement_reuests['projid'];
                                                            $request_id = $rows_rsPayement_reuests['request_id'];
                                                            $payment_requested_date = $rows_rsPayement_reuests['created_at'];
                                                            $payment_status = $rows_rsPayement_reuests['status'];
                                                            $payment_stage = $rows_rsPayement_reuests['stage'];
                                                            $amount_paid = $rows_rsPayement_reuests['requested_amount'];
                                                            $status = $stage = "";
                                                            if ($payment_stage == 1) {
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
                                                            $payment_plan = $rows_rsprojects['payment_plan'];

                                                            $payment_plan_name = "";
                                                            if ($payment_plan == 1) {
                                                                $payment_plan_name = "Milestone";
                                                            } else if ($payment_plan == 2) {
                                                                $payment_plan_name = "Task";
                                                            } else if ($payment_plan == 3) {
                                                                $payment_plan_name = "Project Percentage";
                                                            }

                                                            $query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
                                                            $query_rsPrograms->execute(array(":progid" => $progid));
                                                            $row_rsPrograms = $query_rsPrograms->fetch();
                                                            $totalRows_rsPrograms = $query_rsPrograms->rowCount();

                                                            $project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
                                                            $project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
                                                            $project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";
                                                            $filter_department = $permissions->filter_department_list($project_department, $project_section, $project_directorate);
                                                            if ($filter_department) {
                                                                $counter++;
                                                    ?>
                                                                <tr class="">
                                                                    <td style="width:5%"><?= $counter ?></td>
                                                                    <td style="width:35%"><?= $project_name ?></td>
                                                                    <td style="width:10%"><?= number_format($amount_paid, 2) ?></td>
                                                                    <td style="width:10%"><?= date("Y-m-d", strtotime($payment_requested_date)) ?></td>
                                                                    <td style="width:10%"><?= $payment_plan_name ?></td>
                                                                    <td style="width:10%"><?= $stage ?></td>
                                                                    <td style="width:10%"><?= $status ?></td>
                                                                    <td style="width:10%">
                                                                        <!-- Single button -->
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
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
                                                        } while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch());
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
                                                    $query_rsPayement_reuests =  $db->prepare("SELECT r.*, d.created_at, d.created_by, d.date_paid  FROM tbl_contractor_payment_requests r INNER JOIN tbl_payments_disbursed d ON d.request_id = r.request_id WHERE status = 3");
                                                    $query_rsPayement_reuests->execute();
                                                    $rows_rsPayement_reuests = $query_rsPayement_reuests->fetch();
                                                    $total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();
                                                    if ($total_rsPayement_reuests > 0) {
                                                        $counter = 0;
                                                        do {
                                                            $costline_id = $rows_rsPayement_reuests['id'];
                                                            $projid = $rows_rsPayement_reuests['projid'];
                                                            $request_id = $rows_rsPayement_reuests['request_id'];
                                                            $date_requested = $rows_rsPayement_reuests['created_at'];
                                                            $date_paid = $rows_rsPayement_reuests['date_paid'];
                                                            $created_by = $rows_rsPayement_reuests['created_by'];
                                                            $amount_paid = $rows_rsPayement_reuests['requested_amount'];

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
                                                            $query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
                                                            $query_rsPrograms->execute(array(":progid" => $progid));
                                                            $row_rsPrograms = $query_rsPrograms->fetch();
                                                            $totalRows_rsPrograms = $query_rsPrograms->rowCount();

                                                            $project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
                                                            $project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
                                                            $project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";
                                                            $filter_department = $permissions->filter_department_list($project_department, $project_section, $project_directorate);
                                                            if ($filter_department) {
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
                                                                                    <a type="button" href="#">
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
                                                        } while ($rows_rsPayement_reuests = $query_rsPayement_reuests->fetch());
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
                                    <div class="body" id="budget_line_more_info">

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
                                                    <i class="fa fa-calendar" aria-hidden="true"></i> Request Details
                                                </legend>
                                                <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label for="outcomeIndicator" class="control-label">Project Name *:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="project_name" value="" id="project_name" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                        <label for="tasks" class="control-label">Contractor *:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="contractor_name" value="" id="contractor_name" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                        <label for="location" class="control-label">Contrct Number:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="contractor_number" value="" id="contractor_number" class="form-control" readonly>
                                                        </div>
                                                    </div>

                                                    <div id="milestones">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <input type="hidden" name="task_id[]" id="task_id" value="0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:30%"># </th>
                                                                            <th style="width:15%">Milestone</th>
                                                                            <th style="width:15%">Cost</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="milestone_table">

                                                                    </tbody>
                                                                    <tfoot id="budget_line_foot">
                                                                        <tr>
                                                                            <td colspan="2"><strong>Total</strong></td>
                                                                            <td>
                                                                                <input type="text" name="subtotal_percentage" value="%" id="0_subtotal_percentage" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="tasks">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:30%"># </th>
                                                                            <th style="width:15%">Task</th>
                                                                            <th style="width:15%">Cost</th>
                                                                            <th style="width:5%">
                                                                                <button type="button" name="addplus" id="addplus_financier" onclick="add_budget_costline(0);" class="btn btn-success btn-sm">
                                                                                    <span class="glyphicon glyphicon-plus">
                                                                                    </span>
                                                                                </button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tasks_table">
                                                                        <tr></tr>
                                                                        <tr id="0_removeTr" class="text-center">
                                                                            <td colspan="5">Add Budgetline Costlines</td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tfoot id="budget_line_foot">
                                                                        <tr>
                                                                            <td colspan="1"><strong>Total</strong></td>
                                                                            <td colspan="2">
                                                                                <input type="text" name="subtotal_percentage" value="%" id="0_subtotal_percentage" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="percentage">
                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                            <label for="outcomeIndicator" class="control-label">Milestone *:</label>
                                                            <div class="form-line">
                                                                <input type="text" name="milestone_name" value="" id="milestone_name" class="form-control" readonly>
                                                                <input type="hidden" name="item_id" id="item_id" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                            <label for="tasks" class="control-label">Percentage *:</label>
                                                            <div class="form-line">
                                                                <input type="text" name="milestone_percentage" value="" id="milestone_percentage" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                            <label for="location" class="control-label">Cost:</label>
                                                            <div class="form-line">
                                                                <input type="text" name="milestone_cost" value="" id="milestone_cost" class="form-control" readonly>
                                                                <input type="hidden" name="requested_amount" id="requested_amount" value="">
                                                                <input type="hidden" name="project_plan" id="project_plan" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </fieldset>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                    <i class="fa fa-comment" aria-hidden="true"></i> Add Remarks
                                                </legend>
                                                <div id="comment_section">
                                                    <div class="col-md-12">
                                                        <label class="control-label">Remarks *:</label>
                                                        <br>
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
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="projid" id="projid" value="">
                                    <input type="hidden" name="payment_plan" id="payment_plan" value="">
                                    <input type="hidden" name="stage" id="stage" value="">
                                    <input type="hidden" name="request_id" id="request_id" value="">
                                    <input type="hidden" name="user_name" id="username" value="<?= $user_name ?>">
                                    <input type="hidden" name="contractor_payment" id="contractor_payment" value="new">
                                    <button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value="">Save</button>
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
<script>
    $(document).ready(function() {
        $("#modal_form_submit").submit(function(e) {
            e.preventDefault();
            var form = $(this).serialize();
            $.ajax({
                type: "post",
                url: "ajax/payments/index",
                data: form,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                }
            });
        });
    });

    function get_details(details) {
        var projid = details.projid;
        var payment_plan = details.payment_plan;
        var request_id = details.request_id;
        var project_name = details.project_name;
        var contractor_name = details.contractor_name;
        var contract_no = details.contract_no;

        $("#project_name").val(project_name);
        $("#contractor_name").val(contractor_name);
        $("#contractor_number").val(contract_no);
        $("#projid").val(projid);
        $("#payment_plan").val(payment_plan);
        $("#tasks_table").html("");
        $("#milestone_table").html("");
        $("#milestones").hide();
        $("#tasks").hide();
        $("#percentage").hide();

        $("#milestone_name").val("");
        $("#milestone_percentage").val("");
        $("#milestone_cost").val("");

        if (projid != "" && payment_plan != "") {
            if (payment_plan == "1") {
                get_milestone_details(projid, payment_plan, request_id);
            } else if (payment_plan == 3) {
                get_project_percentage_details(projid, payment_plan, request_id);
                console.log("this is it")
            }
        } else {
            console.log("Project and payment plan are not available")
        }
    }

    function get_milestone_details(projid, payment_plan, request_id) {
        if (projid == "") {
            $.ajax({
                type: "get",
                url: "ajax/payments/index",
                data: {
                    get_direct_cost_details: "get_direct_cost",
                    projid: projid,
                    payment_plan: payment_plan,
                    request_id: request_id
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        if (payment_plan == "1") {
                            $("#milestone_table").html(response.milestone);
                        } else if (payment_plan == "2") {
                            $("#tasks_table").html(response.tasks);
                        } else if (payment_plan == "3") {
                            var project_details = response.project_percentage;
                            $("#milestone_name").val(project_details.milestone);
                            $("#milestone_percentage").val(project_details.percentage);
                            $("#milestone_cost").val(project_details.cost);
                        }
                    } else {
                        console.log(response);
                    }
                }
            });
        }
    }

    function get_task_details(projid, payment_plan, request_id) {
        if (projid == "") {
            $.ajax({
                type: "get",
                url: "ajax/payments/index",
                data: {
                    get_task_details: "get_task_details",
                    projid: projid,
                    payment_plan: payment_plan,
                    request_id: request_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#tasks_table").html(response.tasks);
                    } else {
                        console.log(response);
                    }
                }
            });
        }
    }

    function get_project_percentage_details(projid, payment_plan, request_id) {
        $.ajax({
            type: "get",
            url: "ajax/payments/index",
            data: {
                get_project_percentage_details: "get_project_percentage_details",
                projid: projid,
                payment_plan: payment_plan,
                request_id: request_id
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#milestone_name").val(response.milestone);
                    $("#milestone_percentage").val(response.percentage);
                    $("#milestone_cost").val(response.cost);
                    $("#requested_amount").val(response.requested_amount);
                    $("#item_id").val(response.msid);
                    $("#project_plan").val(response.project_plan);
                } else {
                    console.log("This is what the name of them")
                }
            }
        });
    }

    function get_more_info(payment_id) {
        if (payment_id != "") {
            $.ajax({
                type: "get",
                url: "ajax/payments/index",
                data: {
                    get_contractor_more_info: "get_contractor_more_info",
                    payment_request_id: payment_id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#budget_line_more_info").html(response.details);
                    } else {
                        console.log("data could not be found")
                    }
                }
            });
        }
    }
</script>