<?php
require('includes/head.php');
if ($permission) {
    try {

        function get_unit_of_measure($unit)
        {
            global $db;
            $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
            $query_rsIndUnit->execute(array(":unit_id" => $unit));
            $row_rsIndUnit = $query_rsIndUnit->fetch();
            $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
            return $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
        }

        if (isset($_GET['request_id'])) {
            $encoded_request_id = $_GET['request_id'];
            $decode_request_id = base64_decode($encoded_request_id);
            $request_id_array = explode("projid54321", $decode_request_id);
            $request_id = $request_id_array[1];


            $query_rsPayement_requests =  $db->prepare("SELECT * FROM  tbl_payments_request WHERE id=:request_id LIMIT 1");
            $query_rsPayement_requests->execute(array("request_id" => $request_id));
            $rows_rsPayement_requests = $query_rsPayement_requests->fetch();
            $total_rsPayement_requests = $query_rsPayement_requests->rowCount();

            if ($total_rsPayement_requests > 0) {
                $projid = $rows_rsPayement_requests['projid'];
                $purpose = $rows_rsPayement_requests['purpose'];
                $request_id = $rows_rsPayement_requests['id'];

                $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
                $query_rsProjects->execute(array(":projid" => $projid));
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();

                if ($totalRows_rsProjects > 0) {
                    $implimentation_type = $row_rsProjects['projcategory'];
                    $projname = $row_rsProjects['projname'];
                    $projcode = $row_rsProjects['projcode'];
                    $projcost = $row_rsProjects['projcost'];
                    $project_stage = $row_rsProjects['projstage'];

                    $query_rsStage =  $db->prepare("SELECT * FROM  tbl_project_workflow_stage WHERE priority=:priority");
                    $query_rsStage->execute(array(":priority" => $project_stage));
                    $rows_rsStage = $query_rsStage->fetch();
                    $total_rsStage = $query_rsStage->rowCount();
                    $stage = $total_rsStage > 0 ? $rows_rsStage['stage'] : "";
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
                                                        <li class="list-group-item"><strong>Total Project Year Budget: </strong> Ksh. <?php echo number_format($projcost, 2); ?> </li>
                                                        <li class="list-group-item"><strong>Project Stage: </strong><?= $stage; ?> </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">

                                    <?php

                                    if ($purpose == 1) {


                                        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
                                        $query_Output->execute(array(":projid" => $projid));
                                        $total_Output = $query_Output->rowCount();
                                        if ($total_Output > 0) {
                                            $counter = 0;
                                            while ($row_rsOutput = $query_Output->fetch()) {
                                                $output_id = $row_rsOutput['id'];
                                                $output = $row_rsOutput['indicator_name'];
                                                $indicator_mapping_type = $row_rsOutput['indicator_mapping_type'];

                                                $query_rsPayement_requests =  $db->prepare("SELECT r.unit_cost, r.no_of_units, d.unit, d.description FROM tbl_project_direct_cost_plan d INNER JOIN tbl_payments_request_details r ON r.direct_cost_id = d.id WHERE  outputid=:output_id AND request_id =:request_id");
                                                $query_rsPayement_requests->execute(array(":output_id" => $output_id, ":request_id" => $request_id));
                                                $total_rsPayement_requests = $query_rsPayement_requests->rowCount();

                                                if ($total_rsPayement_requests > 0) {
                                                    $counter++;
                                    ?>
                                                    <fieldset class="scheduler-border">
                                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                            <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
                                                        </legend>
                                                        <?php
                                                        if ($indicator_mapping_type == 1 || $indicator_mapping_type == 3) {
                                                            $querysSite = $db->prepare("SELECT * FROM tbl_project_sites d INNER JOIN tbl_output_disaggregation o ON d.site_id = o.output_site INNER JOIN tbl_state s ON s.id = d.state_id WHERE o.projid = :projid AND outputid = :output_id");
                                                            $querysSite->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                            $totalsSite = $querysSite->rowCount();
                                                            if ($totalsSite > 0) {
                                                                $scounter = 0;
                                                                while ($row_rsSite = $querysSite->fetch()) {
                                                                    $site_id = $row_rsSite['site_id'];
                                                                    $state_id = $row_rsSite['state_id'];
                                                                    $site = $row_rsSite['site'];
                                                                    $state = $row_rsSite['state'];

                                                                    $query_rsPayement_requests =  $db->prepare("SELECT r.unit_cost, r.no_of_units, d.unit, d.description, r.direct_cost_id FROM tbl_project_direct_cost_plan d INNER JOIN tbl_payments_request_details r ON r.direct_cost_id = d.id WHERE outputid=:output_id AND site_id=:site_id AND request_id =:request_id");
                                                                    $query_rsPayement_requests->execute(array(":output_id" => $output_id, ":site_id" => $site_id, ":request_id" => $request_id));

                                                                    $total_rsPayement_requests = $query_rsPayement_requests->rowCount();
                                                                    if ($total_rsPayement_requests > 0) {
                                                                        $scounter++;
                                                        ?>
                                                                        <fieldset class="scheduler-border">
                                                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                                                <i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $scounter ?> : <?= $site ?>
                                                                            </legend>
                                                                            <?php
                                                                            ?>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th style="width:40%">Description </th>
                                                                                            <th style="width:40%">No. of Units</th>
                                                                                            <th style="width:15%">Unit Cost</th>
                                                                                            <th style="width:15%">Total Cost</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        while ($rows_rsPayement_requests = $query_rsPayement_requests->fetch()) {
                                                                                            $direct_cost_id = $rows_rsPayement_requests['direct_cost_id'];
                                                                                            $no_of_units = $rows_rsPayement_requests['no_of_units'];
                                                                                            $description = $rows_rsPayement_requests['description'];
                                                                                            $unit = $rows_rsPayement_requests['unit'];
                                                                                            $unit_cost = $rows_rsPayement_requests['unit_cost'];
                                                                                            $units_no = $rows_rsPayement_requests['no_of_units'];
                                                                                            $total_cost = $unit_cost * $no_of_units;
                                                                                            $unit_of_measure = get_unit_of_measure($unit);
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td style="width:40%"><?= $description ?> </td>
                                                                                                <td style="width:30%"><?= number_format($units_no, 2) . " " . $unit_of_measure ?></td>
                                                                                                <td style="width:15%"><?= number_format($unit_cost, 2) ?></td>
                                                                                                <td style="width:15%"><?= number_format($total_cost, 2) ?></td>
                                                                                            </tr>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </fieldset>
                                                            <?php
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            $counter++;
                                                            ?>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:40%">Description </th>
                                                                            <th style="width:40%">No. of Units</th>
                                                                            <th style="width:15%">Unit Cost</th>
                                                                            <th style="width:15%">Total Cost</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        while ($rows_rsPayement_requests = $query_rsPayement_requests->fetch()) {
                                                                            $direct_cost_id = $rows_rsPayement_requests['direct_cost_id'];
                                                                            $no_of_units = $rows_rsPayement_requests['no_of_units'];
                                                                            $description = $rows_rsPayement_requests['description'];
                                                                            $unit = $rows_rsPayement_requests['unit'];
                                                                            $unit_cost = $rows_rsPayement_requests['unit_cost'];
                                                                            $units_no = $rows_rsPayement_requests['no_of_units'];
                                                                            $total_cost = $unit_cost * $no_of_units;
                                                                            $unit_of_measure = get_unit_of_measure($unit);
                                                                        ?>
                                                                            <tr>
                                                                                <td style="width:40%"><?= $description ?> </td>
                                                                                <td style="width:30%"><?= number_format($units_no, 2) . " " . $unit_of_measure ?></td>
                                                                                <td style="width:15%"><?= number_format($unit_cost, 2) ?></td>
                                                                                <td style="width:15%"><?= number_format($total_cost, 2) ?></td>
                                                                            </tr>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </fieldset>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-calendar" aria-hidden="true"></i> Request Details
                                            </legend>
                                            <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <label class="control-label">Due Date <span id="impunit"></span>*:</label>
                                                    <div class="form-input">
                                                        <input type="date" name="due_date" value="" id="due_date" class="form-control" required="required">
                                                    </div>
                                                </div>
                                                <div id="comment_section">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="control-label">Remarks *:</label>
                                                        <br>
                                                        <div class="form-line">
                                                            <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <div class="col-md-12 text-center">
                                                        <button type="button" class="btn btn-success">Request</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </fieldset>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                <thead>
                                                    <tr>
                                                        <th style="width:5%"># </th>
                                                        <th style="width:30%">Description </th>
                                                        <th style="width:20%">No. of Units</th>
                                                        <th style="width:10%">Unit Cost</th>
                                                        <th style="width:10%">Total Cost</th>
                                                        <th style="width:5%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query_rsPayement_requests =  $db->prepare("SELECT r.unit_cost, r.no_of_units, d.unit, d.description, r.direct_cost_id, r.id as sub_request_id FROM tbl_project_direct_cost_plan d INNER JOIN tbl_payments_request_details r ON r.direct_cost_id = d.id WHERE request_id =:request_id");
                                                    $query_rsPayement_requests->execute(array(":request_id" => $request_id));
                                                    $total_rsPayement_requests = $query_rsPayement_requests->rowCount();
                                                    if ($total_rsPayement_requests > 0) {
                                                        $counter = 0;
                                                        while ($rows_rsPayement_requests = $query_rsPayement_requests->fetch()) {
                                                            $counter++;
                                                            $direct_cost_id = $rows_rsPayement_requests['direct_cost_id'];
                                                            $no_of_units = $rows_rsPayement_requests['no_of_units'];
                                                            $description = $rows_rsPayement_requests['description'];
                                                            $unit = $rows_rsPayement_requests['unit'];
                                                            $unit_cost = $rows_rsPayement_requests['unit_cost'];
                                                            $units_no = $rows_rsPayement_requests['no_of_units'];
                                                            $sub_request_id = $rows_rsPayement_requests['sub_request_id'];
                                                            $total_cost = $unit_cost * $no_of_units;
                                                            $unit_of_measure = get_unit_of_measure($unit);
                                                    ?>
                                                            <tr>
                                                                <td style="width:5%"><?= $counter ?></td>
                                                                <td style="width:30%"><?= $description ?> </td>
                                                                <td style="width:20%"><?= number_format($no_of_units, 2) . "  "  . $unit_of_measure ?></td>
                                                                <td style="width:10%"><?= number_format($unit_cost, 2) ?></td>
                                                                <td style="width:10%"><?= number_format(($no_of_units * $unit_cost), 2) ?></td>
                                                                <td style="width:5%">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="edit_item(<?= $request_id ?>,'<?= $sub_request_id ?>', '<?= htmlspecialchars($description) ?>')">
                                                                                    <i class="fa fa-check"></i> Edit
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a type="button" onclick="destroy_item('<?= $description ?>','<?= $sub_request_id ?>','<?= $subtask_id ?>')">
                                                                                    <i class="fa fa-check"></i> Delete
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
                                        <?php
                                    }

                                    $query_rsComments = $db->prepare("SELECT * FROM tbl_payment_request_comments WHERE request_id=:request_id ORDER BY id DESC");
                                    $query_rsComments->execute(array(":request_id" => $request_id));
                                    $totalRows_rsComments = $query_rsComments->rowCount();
                                    $comments = '';
                                    if ($totalRows_rsComments > 0) {
                                        $counter = 0;
                                        while ($Rows_rsComments = $query_rsComments->fetch()) {
                                            $counter++;
                                            $comment = $Rows_rsComments['comments'];
                                            $role = $Rows_rsComments['role'];
                                            $created_by = $Rows_rsComments['created_by'];
                                            $created_at = $Rows_rsComments['created_at'];

                                            $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
                                            $query_rsPMbrs->execute(array(":user_id" => $created_by));
                                            $row_rsPMbrs = $query_rsPMbrs->fetch();
                                            $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
                                            $full_name = $count_row_rsPMbrs > 0 ? $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";
                                        ?>
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <ul class="list-group">
                                                        <li class="list-group-item list-group-item list-group-item-action active">Comment By:<?= $full_name ?></li>
                                                        <li class="list-group-item"><strong>Role: </strong> <?= $role ?></li>
                                                        <li class="list-group-item"><strong>Comment: </strong> <?= $comment ?></li>
                                                        <li class="list-group-item"><strong>Comment Date: </strong> <?= date('d M Y', strtotime($created_at)) ?> </li>
                                                    </ul>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }

                                    ?>
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
                                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                <label for="outcomeIndicator" class="control-label" id="ceiling_text">Project Ceiling *:</label>
                                                                <div class="form-line">
                                                                    <input type="hidden" name="project_ceiling_amount" id="project_ceiling_amount" value="<?= $projcost ?>">
                                                                    <input type="text" name="project_amount_ceiling" value="<?= number_format($projcost, 2) ?>" id="project_amount_ceiling" class="form-control" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                <label for="outcomeIndicator" class="control-label" id="ceiling_text">Year Ceiling *:</label>
                                                                <div class="form-line">
                                                                    <input type="hidden" name="ceiling_amount" id="ceiling_amount" value="<?= $projcost ?>">
                                                                    <input type="text" name="amount_ceiling" value="<?= number_format($projcost, 2) ?>" id="amount_ceiling" class="form-control" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row clearfix">
                                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="output_div">
                                                                    <label for="output" class="control-label">Output *:</label>
                                                                    <div class="form-line">
                                                                        <select name="output" id="output" class="form-control show-tick" onchange="get_sites()" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                                            <option value="">.... Select from list ....</option>
                                                                            <?php
                                                                            $outputs = '<option value="">Select Output from list</option>';
                                                                            $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
                                                                            $query_Output->execute(array(":projid" => $projid));
                                                                            $total_Output = $query_Output->rowCount();
                                                                            $success = false;
                                                                            $outputs = '<option value="">.... Select Output ....</option>';
                                                                            if ($total_Output > 0) {
                                                                                $success = true;
                                                                                while ($row_rsOutput = $query_Output->fetch()) {
                                                                                    $output_id = $row_rsOutput['id'];
                                                                                    $output = $row_rsOutput['indicator_name'];
                                                                            ?>
                                                                                    <option value="<?= $output_id ?>"><?= $output ?></option>';
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="site_div">
                                                                    <label for="site_id" class="control-label">Site *:</label>
                                                                    <div class="form-line">
                                                                        <select name="site_id" id="site_id" class="form-control show-tick" onchange="get_tasks()" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                                            <option value="">.... Select from list ....</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="tasks_div">
                                                                    <label for="tasks" class="control-label">Tasks *:</label>
                                                                    <div class="form-line">
                                                                        <select name="tasks[]" id="tasks" class="form-control show-tick selectpicker" onchange="get_tasks_budgetlines()" multiple style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false">
                                                                            <option value="">.... Select from list ....</option>
                                                                        </select>
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
                                                                                <tr id="_removeTr" class="text-center">
                                                                                    <td colspan="5">Add Budgetline Costlines</td>
                                                                                </tr>
                                                                            </tbody>
                                                                            <tfoot id="budget_line_foot">
                                                                                <tr>
                                                                                    <td colspan="1"><strong>Sub Total</strong></td>
                                                                                    <td colspan="1">
                                                                                        <input type="text" name="subtotal_amount1" value="" id="sub_total_amount" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                                    </td>
                                                                                    <td colspan="1"> <strong>% Sub Total</strong></td>
                                                                                    <td colspan="2">
                                                                                        <input type="text" name="subtotal_percentage" value="%" id="subtotal_percentage" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
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
                                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                <input type="hidden" name="project_stage" id="project_stage" value="<?= $project_stage ?>">
                                                <input type="hidden" name="amount_requested" id="amount_requested" value="">
                                                <input type="hidden" name="request_id" id="request_id" value="">
                                                <input type="hidden" name="cost_type" id="cost_type" value="">
                                                <input type="hidden" name="user_name" id="username" value="<?= $user_name ?>">
                                                <input type="hidden" name="store" id="store" value="new">
                                                <input type="hidden" name="project_purpose" id="project_purpose" value="">
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

<script src="assets/js/payment/inhouse-request.js"></script>