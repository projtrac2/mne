<?php
    try {

require('includes/head.php');
if ($permission) {
        if (isset($_GET['projid'])) {
            $encoded_projid = $_GET['projid'];
            $decode_projid = base64_decode($encoded_projid);
            $projid_array = explode("projid54321", $decode_projid);
            $projid = $projid_array[1];
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


                function get_unit_of_measure($unit)
                {
                    global $db;
                    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                    $query_rsIndUnit->execute(array(":unit_id" => $unit));
                    $row_rsIndUnit = $query_rsIndUnit->fetch();
                    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                    return $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
                }
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

                                $query_rsPayement_requests =  $db->prepare("SELECT * FROM  tbl_payments_request WHERE status =0  AND stage=0 AND projid=:projid LIMIT 1");
                                $query_rsPayement_requests->execute(array("projid" => $projid));
                                $rows_rsPayement_requests = $query_rsPayement_requests->fetch();
                                $total_rsPayement_requests = $query_rsPayement_requests->rowCount();

                                if ($total_rsPayement_requests > 0) {
                                    $purpose = $rows_rsPayement_requests['purpose'];
                                    $request_id = $rows_rsPayement_requests['id'];
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
                                                    if ($indicator_mapping_type == 1 ) {
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

                                                                var_dump($total_rsPayement_requests);

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
                                                                                        <th style="width:30%">Description </th>
                                                                                        <th style="width:15%">Unit</th>
                                                                                        <th style="width:15%">Unit Cost</th>
                                                                                        <th style="width:10%">No. of Units</th>
                                                                                        <th style="width:10%">Remaining Units</th>
                                                                                        <th style="width:15%">Total Cost</th>
                                                                                        <th style="width:5%">
                                                                                        </th>
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
                                                                                        $remaining_units = 0;
                                                                                    ?>
                                                                                        <tr>
                                                                                            <td style="width:30%"><?= $description ?> </td>
                                                                                            <td style="width:15%"><?= $unit_of_measure ?></td>
                                                                                            <td style="width:15%"><?= number_format($unit_cost, 2) ?></td>
                                                                                            <td style="width:10%"><?= number_format($units_no, 2) ?></td>
                                                                                            <td style="width:10%"><?= number_format($remaining_units, 2) ?></td>
                                                                                            <td style="width:15%"><?= number_format($total_cost, 2) ?></td>
                                                                                            <td style="width:5%"></td>
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
                                                        ?>

                                                    <?php
                                                    } else {
                                                        $counter++;
                                                    ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width:30%">Description </th>
                                                                        <th style="width:15%">Unit</th>
                                                                        <th style="width:15%">Unit Cost</th>
                                                                        <th style="width:10%">No. of Units</th>
                                                                        <th style="width:10%">Remaining Units</th>
                                                                        <th style="width:15%">Total Cost</th>
                                                                        <th style="width:5%">
                                                                        </th>
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
                                                                        $remaining_units = 0;
                                                                    ?>
                                                                        <tr>
                                                                            <td style="width:30%"><?= $description ?> </td>
                                                                            <td style="width:15%"><?= $unit_of_measure ?></td>
                                                                            <td style="width:15%"><?= number_format($unit_cost, 2) ?></td>
                                                                            <td style="width:10%"><?= number_format($units_no, 2) ?></td>
                                                                            <td style="width:10%"><?= number_format($remaining_units, 2) ?></td>
                                                                            <td style="width:15%"><?= number_format($total_cost, 2) ?></td>
                                                                            <td style="width:5%"></td>
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
                                <?php

                                } else {
                                ?>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-calendar" aria-hidden="true"></i> Budgetline Details
                                        </legend>
                                        <?php
                                        if ($project_stage == 10) {
                                        ?>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="purpose_div">
                                                <label for="purpose" class="control-label">Purpose *:</label>
                                                <div class="form-line">
                                                    <select name="purpose" id="purpose" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                        <option value="">.... Select from list ....</option>
                                                        <?php
                                                        if ($implimentation_type == 1) {
                                                        ?>
                                                            <option value="1">Direct Cost</option>
                                                        <?php
                                                        }
                                                        ?>
                                                        <option value="2">Administrative/Operational Cost</option>
                                                        <option value="4">Monitoring</option>
                                                        <option value="5">Endline Baseline</option>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php
                                        } else {
                                            $purpose = 0;
                                            if ($project_stage == 8) {
                                                $purpose = 3;
                                            } else if ($project_stage == 9) {
                                                $purpose = 5;
                                            }
                                        ?>
                                            <input type="hidden" name="purpose" value="<?= $purpose ?>" id="purpose">
                                        <?php
                                        }
                                        ?>

                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label for="purpose" class="control-label"></label>
                                            <div class="form-input">
                                                <a type="button" data-toggle="modal" class="btn btn-primary" id="moreItemModalBtn" data-target="#addFormModal" onclick="add_request_details()">
                                                    <i class="fa fa-plus"></i> Create a request
                                                </a>
                                            </div>
                                        </div>

                                    </fieldset>
                                <?php
                                }
                                ?>
                                <fieldset class="scheduler-border" id="direct_cost">
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

require('includes/footer.php');

} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>

<script>
    var ajax_url = "ajax/payments/index";
    $(document).ready(function() {
        $("#modal_form_submit").submit(function(e) {
            e.preventDefault();
            var cost_type = $("#purpose").val();
            $.ajax({
                type: "post",
                url: ajax_url,
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        success_alert("Approved payment successfully");
                    } else {
                        error_alert("Approval error !!");
                    }
                    $(".modal").each(function() {
                        $(this).modal("hide");
                        $(this)
                            .find("form")
                            .trigger("reset");
                    });

                    setTimeout(() => {
                        if (cost_type == 1) {
                            window.location.reload();
                        } else {
                            window.location.href = "inhouse-payment-requests.php";
                        }
                    }, 3000);
                }
            });
        });
        $("#direct_cost").hide();

        // check if the input has already been selected
        $(document).on("change", ".description", function(e) {
            var tralse = true;
            var select_funding_arr = [];
            var attrb = $(this).attr("id");
            var selectedid = "#" + attrb;
            var selectedText = $(selectedid + " option:selected").html();

            $(".description").each(function(k, v) {
                var getVal = $(v).val();
                if (getVal && $.trim(select_funding_arr.indexOf(getVal)) != -1) {
                    tralse = false;
                    swal("Warning", "You canot select budgetline " + selectedText + " more than once ", "warning");
                    var rw = $(v).attr("data-id");
                    var target = rw;
                    $(v).val("");
                    return false;
                } else {
                    select_funding_arr.push($(v).val());
                }
            });
            if (!tralse) {
                return false;
            }
        });
    });


    //function to put commas to the data
    function commaSeparateNumber(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
        }
        return val;
    }

    function get_tasks_budgetlines() {
        var tasks = $("#tasks").val();
        $("#budgetline_div").hide();

        if (tasks.length > 0) {
            $("#budgetline_div").show();
        }
    }

    const add_request_details = () => {
        var purpose = $("#purpose").val();
        if (purpose != '') {
            get_details();
            $("#output_div").hide();
            $("#site_div").hide();
            $("#tasks_div").hide();
            $("#output").removeAttr("required");
            $("#site_id").removeAttr("required");
            $("#tasks").removeAttr("required");
            $("#tasks").html("");
            $(".selectpicker").selectpicker("refresh");
            $("#store").val("new");
            $("#budgetline_div").show();
            $("#project_commets_div").show();
            $("#comment").attr("required", "required");
            $("#cost_type").val(purpose);
            if (purpose == '1') {
                $("#project_commets_div").hide();
                $("#budgetline_div").hide();
                $("#output_div").show();
                $("#output").attr('required', 'required');
                $("#comment").removeAttr('required');
            }

        } else {
            error_alert("Please select a purpose");
            setTimeout(() => {
                $(".modal").each(function() {
                    $(this).modal("hide");
                    $(this)
                        .find("form")
                        .trigger("reset");
                });
            }, 3000);
            $("#cost_type").val('');
        }
    }

    //
    function get_sites() {
        var projid = $("#projid").val();
        var output_id = $("#output").val();
        $("#site_div").hide();
        $("#tasks_div").hide();
        $("#tasks").html("");
        $(".selectpicker").selectpicker("refresh");
        if (output_id != '') {
            $.ajax({
                type: "get",
                url: "ajax/payments/index",
                data: {
                    get_sites: "get_sites",
                    projid: projid,
                    output_id: output_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        var mapping_type = response.mapping_type;
                        if (mapping_type == 1 ) {
                            $("#site_div").show();
                            $("#site_id").html(response.sites);
                        } else {
                            $("#tasks_div").show();
                            $("#tasks").html(response.tasks);
                            $(".selectpicker").selectpicker("refresh");
                        }
                    } else {
                        console.log("Error");
                    }
                }
            });
        } else {
            console.log("Error, please select an output");
        }
    }

    function get_tasks() {
        var projid = $("#projid").val();
        var output_id = $("#output").val();
        var site_id = $("#site_id").val();
        $("#tasks_div").hide();
        if (site_id != '') {
            $.ajax({
                type: "get",
                url: "ajax/payments/index",
                data: {
                    get_tasks: "get_tasks",
                    projid: projid,
                    output_id: output_id,
                    site_id: site_id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#tasks_div").show();
                        $("#tasks").html(response.tasks);
                        $(".selectpicker").selectpicker("refresh");
                    } else {
                        console.log("Error");
                    }
                }
            });
        } else {
            console.log("Error, please select an output");
        }
    }

    function get_details() {
        var projid = $("#projid").val();
        var stage = $("#stage").val();
        var request_id = $("#request_id").val();
        var purpose = $("#purpose").val();
        if (projid != "") {
            $.ajax({
                type: "get",
                url: "ajax/payments/index",
                data: {
                    get_details: "get_details",
                    projid: projid,
                    stage: stage,
                    purpose: purpose,
                    request_id: request_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#site_id").html(response.sites);
                        $("#personnel").html(response.personnel);
                        // edit_payment(response);
                    } else {
                        error_alert("Error please ensure that you use the correct project");
                    }
                }
            });
        }
    }


    // function to add new rowfor financiers
    function add_budget_costline() {
        var stage = $("#stage").val();
        var purpose = $("#purpose").val();
        var result = false;
        var task = '';
        result = false;
        var msg = "Please select a purpose first";
        if (purpose != '') {
            result = true;
            if (stage == '10') {
                if (purpose == 1) {
                    task = $("#tasks").val();
                    result = task != '' ? true : false;
                    msg = task == '' ? "Please select a task first" : '';
                }
            }
        }

        if (result) {
            // $("#cost_type").val(purpose);
            $(`#_removeTr`).remove(); //new change
            $rowno = $(`#_budget_lines_values_table tr`).length;
            $rowno += 1;
            $rowno = $rowno;
            $(`#_budget_lines_values_table tr:last`).after(`
                <tr id="budget_line_cost_line${$rowno}">
                    <td>
                        <select name="direct_cost_id[]" id="description${$rowno}" data-id="${$rowno}" onchange="get_costline_details(${$rowno})" class="form-control show-tick description" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                            <option value="">.... Select from list ....</option>
                        </select>
                    </td>
                    <td id="unit${$rowno}"> </td>
                    <td>
                        <input type="number" name="unit_cost[]" min="0" class="form-control " id="unit_cost${$rowno}" onchange="calculate_total_cost(${$rowno})" onkeyup="calculate_total_cost(${$rowno})">
                    </td>
                    <td>
                        <input type="number" name="no_units[]" min="0" class="form-control " onchange="calculate_total_cost(${$rowno})" onkeyup="calculate_total_cost(${$rowno})" id="no_units${$rowno}">
                    </td>
                        <td>
                        <input type="hidden" name="h_no_units[]" id="h_no_units${$rowno}">
                        <input type="text" name="p_no_units[]" min="0" class="form-control " id="p_no_units${$rowno}" readonly>
                    </td>
                    <td>
                            <input type="hidden" name="subtotal_amount[]" id="subtotal_amount${$rowno}" class="subamount sub" value="">
                            <span id="subtotal_cost${$rowno}" style="color:red"></span>
                    </td>
                    <td style="width:2%">
                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_budget_costline('budget_line_cost_line${$rowno}', '')">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </td>
                </tr>`);
            get_budgetline_details($rowno, purpose);
        } else {
            error_alert(msg);
        }
    }

    // function to delete financiers row
    function delete_budget_costline(rowno) {
        $("#" + rowno).remove();
        var check = $(`#_budget_lines_values_table tr`).length;
        if (check == 1) {
            $(`#_budget_lines_values_table`).html(`
        <tr></tr>
            <tr id="_removeTr" class="text-center">
            <td colspan="7">Add Budgetline Costlines</td>
        </tr>`);
        }
        calculate_subtotal();
    }

    function get_budgetline_details(rowno, task_id, cost_type) {
        var projid = $("#projid").val();
        var output_id = $("#output").val();
        var site_id = $("#site_id").val();
        var task_id = $("#tasks").val();
        var stage = $("#stage").val();
        var cost_type = $("#purpose").val();

        if (projid != "") {
            $.ajax({
                type: "get",
                url: ajax_url,
                data: {
                    get_budgetline_info: "get_budgetline_info",
                    projid: projid,
                    output_id: output_id,
                    site_id: site_id,
                    task_id: task_id,
                    stage: stage,
                    purpose: cost_type,
                    cost_type: cost_type,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $(`#description${rowno}`).html(response.description);
                    } else {
                        console.log("Error please ensure that you use the correct project")
                    }
                }
            });
        } else {
            console.log("Ensure you have the correct project");
        }
    }


    function get_costline_details(rowno) {
        var description = $(`#description${rowno}`).val();
        var projid = $("#projid").val();
        if (description != "") {
            $.ajax({
                type: "get",
                url: "ajax/payments/index",
                data: {
                    get_costline_details: "get_costline_details",
                    projid: projid,
                    direct_cost_id: description,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        var description = $(`#description${rowno}`).val();
                        if (description != "") {
                            var budgetline_details = response.budgetline_details;
                            var unit_cost = budgetline_details.unit_cost;
                            var unit = budgetline_details.unit_of_measure;
                            $(`#p_no_units${rowno}`).val(response.remaining_units);
                            $(`#h_no_units${rowno}`).val(response.remaining_units);
                            $(`#unit_cost${rowno}`).val(unit_cost);
                            $(`#h_unit_cost${rowno}`).val(unit_cost);
                            $(`#unit${rowno}`).html(unit);
                            $(`#subtotal_amount${rowno}`).val("");
                            $(`#subtotal_cost${rowno}`).html("");
                        } else {
                            console.log("data could not be found")
                            $(`#unit_cost${rowno}`).val("");
                            $(`#no_units${rowno}`).val("");
                            $(`#subtotal_amount${rowno}`).val("");
                            $(`#subtotal_cost${rowno}`).val("");
                        }
                    } else {
                        console.log("data could not be found");
                        $(`#unit_cost${rowno}`).val("");
                        $(`#no_units${rowno}`).val("");
                        $(`#subtotal_amount${rowno}`).val("");
                        $(`#subtotal_cost${rowno}`).val("");
                    }
                    calculate_subtotal();
                }
            });
        } else {
            console.log("Please select the description please");
        }
    }

    function calculate_total_cost(rowno) {
        var unit_cost = $(`#unit_cost${rowno}`).val();
        var no_units = $(`#no_units${rowno}`).val();
        var h_no_units = $(`#h_no_units${rowno}`).val();
        var ceiling_amount = $("#ceiling_amount").val();

        unit_cost = (unit_cost != "") ? parseFloat(unit_cost) : 0;
        no_units = (no_units != "") ? parseFloat(no_units) : 0;
        h_no_units = (h_no_units != "") ? parseFloat(h_no_units) : 0;
        var total = 0;
        if (h_no_units >= no_units && no_units > 0) {
            if (ceiling_amount != "") {
                ceiling_amount = parseFloat(ceiling_amount);
                total = no_units * unit_cost;

                $(`#subtotal_amount${rowno}`).val(total);
                var total_amount = 0;
                $(`.subamount`).each(function() {
                    if ($(this).val() != "") {
                        total_amount = total_amount + parseFloat($(this).val());
                    }
                });
                var remainder = ceiling_amount - total_amount;
                if (remainder < 0) {
                    $(`#no_units${rowno}`).val("");
                    total = 0;
                }
            } else {
                error_alert("Error check the data");
                $(`#no_units${rowno}`).val("");
            }
        } else {
            error_alert("Sorry ensure that the number of units is less or equal to the number of units specified");
            $(`#no_units${rowno}`).val("");
        }
        $(`#subtotal_amount${rowno}`).val(total);
        $(`#subtotal_cost${rowno}`).html(commaSeparateNumber(total));
        calculate_subtotal(rowno);
    }

    function calculate_subtotal() {
        var ceiling_amount = $("#ceiling_amount").val();
        var total_amount = 0;
        $(`.sub`).each(function() {
            if ($(this).val() != "") {
                total_amount = total_amount + parseFloat($(this).val());
            }
        });
        var percentage = ((total_amount / ceiling_amount) * 100);
        $(`#sub_total_amount`).val(commaSeparateNumber(total_amount));
        $(`#subtotal_percentage`).val(percentage);
    }
</script>