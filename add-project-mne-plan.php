<?php

require('includes/head.php');
if ($permission && (isset($_GET['projid']) && !empty($_GET["projid"]))) {
    try {
        $decode_projid =  base64_decode($_GET['projid']);
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid AND projstage=:projstage");
        $query_rsProjects->execute(array(":projid" => $projid, ":projstage" => $workflow_stage));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        if ($totalRows_rsProjects > 0) {
            $progid = $row_rsProjects['progid'];
            $projcode = $row_rsProjects['projcode'];
            $project = $row_rsProjects['projname'];
            $projfscyear = $row_rsProjects['projfscyear'];
            $projduration = $row_rsProjects['projduration'];
            $projevaluation = $row_rsProjects['projevaluation'];
            $projimpact = $row_rsProjects['projimpact'];
            $projstartdate = $row_rsProjects['projstartdate'];
            $projenddate = $row_rsProjects['projenddate'];
            $project_stage_id = $row_rsProjects['stage_id'];

            $end_year_moth = date("m", strtotime($projenddate));
            $end_year = date("Y", strtotime($projenddate));
            $query_rsFscYear =  $db->prepare("SELECT id, yr, year FROM tbl_fiscal_year WHERE id >=:id AND yr <=:yr ORDER BY id");
            $query_rsFscYear->execute(array(":id" => $projfscyear, ":yr" => $end_year));
            $total_rsFscYear = $query_rsFscYear->rowCount();
            $output_options = '';
            if ($total_rsFscYear > 0) {
                while ($row_rsFscYear = $query_rsFscYear->fetch()) {
                    $output_options .= '<option value="' . $row_rsFscYear['id'] . '">' . $row_rsFscYear['year'] . '</option>';
                }
            }


            //=============================================== IMPACT SECTION ==============================================================================

            $query_impactIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Impact' AND indicator_type=2 AND active = '1'  ORDER BY indid");
            $query_impactIndicators->execute();
            $row_impactIndicators = $query_impactIndicators->fetch();
            $totalRows_impactIndicators = $query_impactIndicators->rowCount();

            //=============================================== OUTCOME SECTION ==============================================================================
            $query_rsOutcomeIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Outcome' AND indicator_type=2 AND active = '1' ORDER BY indid");
            $query_rsOutcomeIndicators->execute();
            $totalRows_rsOutcomeIndicators = $query_rsOutcomeIndicators->rowCount();


            function mne_plan()
            {
                global $db, $projid, $projevaluation, $projimpact;
                $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid ");
                $query_rsOutput->execute(array(":projid" => $projid));
                $totalRows_rsOutput = $query_rsOutput->rowCount();
                $output = $totalRows_rsOutput > 0 ? true : false;
                $outcome = true;
                if ($projevaluation == 1) {
                    $sql = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE projid = :projid ORDER BY `id` ASC");
                    $sql->execute(array(":projid" => $projid));
                    $rows_count = $sql->rowCount();
                    $outcome = $rows_count > 0 ? true : false;
                }

                $impact = true;
                if ($projimpact == 1) {
                    $sql = $db->prepare("SELECT * FROM `tbl_project_expected_outcome_details` WHERE projid = :projid ORDER BY `id` ASC");
                    $sql->execute(array(":projid" => $projid));
                    $row_count = $sql->rowCount();
                    $impact = $row_count > 0 ? true : false;
                }
                return $output && $outcome && $impact ? true : false;
            }

?>
            <!-- start body  -->
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon  . ' ' . $pageTitle ?>
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
                                                <li class="list-group-item list-group-item list-group-item-action active"> Name: <?= $project ?> </li>
                                                <li class="list-group-item"><strong> Code: </strong> <?= $projcode ?> </li>
                                                <input type="hidden" name="myprojid" id="myprojid" value="<?= $projid ?>">
                                                <input type="hidden" name="key_unique" id="key_unique" value="<?= $projid ?>">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs" style="font-size:14px">
                                            <li class="active">
                                                <a data-toggle="tab" href="#output"><i class="fa fa-list bg-blue" aria-hidden="true"></i> Output/s&nbsp;<span class="badge bg-blue">|</span></a>
                                            </li>
                                            <?php
                                            if ($projevaluation == 1) {
                                            ?>
                                                <li class="">
                                                    <a data-toggle="tab" href="#outcome"><i class="fa fa-tasks bg-indigo" aria-hidden="true"></i> Outcome/s&nbsp;<span class="badge bg-indigo">|</span></a>
                                                </li>
                                            <?php
                                            }
                                            if ($projimpact == 1) {
                                            ?>
                                                <li class="">
                                                    <a data-toggle="tab" href="#impact"><i class="fa fa-id-card-o bg-green" aria-hidden="true"></i> Impact/s &nbsp;<span class="badge bg-green">|</span></a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="tab-content">
                                        <div id="output" class="tab-pane fade in active">
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover" id="output_table" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="3%">#</th>
                                                                    <th width="47%">Output</th>
                                                                    <th width="30%">Indicator</th>
                                                                    <th width="15%">Other Details</th>
                                                                    <th width="5%">
                                                                        <button type="button" name="addplus" id="addplus_output" onclick="add_row_output('<?= $projid ?>');" class="btn btn-success btn-sm">
                                                                            <span class="glyphicon glyphicon-plus">
                                                                            </span>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="output_table_body">
                                                                <tr></tr>
                                                                <?php
                                                                $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid =:projid ORDER BY id");
                                                                $query_rsOutput->execute(array(":projid" => $projid));
                                                                $totalRows_rsOutput = $query_rsOutput->rowCount();
                                                                if ($totalRows_rsOutput > 0) {
                                                                    $rowno = 0;
                                                                    while ($row_rsOutput = $query_rsOutput->fetch()) {
                                                                        $rowno++;
                                                                        $outputid = $row_rsOutput['outputid'];
                                                                        $opids = $row_rsOutput['id'];
                                                                        $indicatorID = $row_rsOutput['indicator'];

                                                                        $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid =:indid ");
                                                                        $query_Indicator->execute(array(":indid" => $indicatorID));
                                                                        $row = $query_Indicator->fetch();
                                                                        $indicator_name = $row ? $row['indicator_name'] : "";
                                                                        $indicator_unit = $row ? $row['indicator_unit'] : "";

                                                                        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id =:indicator_unit");
                                                                        $query_rsIndUnit->execute(array(":indicator_unit" => $indicator_unit));
                                                                        $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                        $unit_of_measure = ($totalRows_rsIndUnit > 0) ? $row_rsIndUnit['unit'] : "";
                                                                ?>
                                                                        <tr id="row<?= $rowno ?>">
                                                                            <td>
                                                                                <?= $rowno ?>
                                                                            </td>
                                                                            <td>
                                                                                <select data-id="<?= $rowno ?>" name="output[]" class="form-control validoutcome select_output" id="outputrow<?= $rowno ?>" onchange='getIndicator("row<?= $rowno ?>")' class="form-control validoutcome selectOutcome" required="required" disabled>
                                                                                    <option value="">Select Output from list</option>
                                                                                    <?php
                                                                                    $query_rsprogOutput =  $db->prepare("SELECT * FROM  tbl_progdetails WHERE progid=:progid GROUP BY indicator ");
                                                                                    $query_rsprogOutput->execute(array(":progid" => $progid));
                                                                                    $totalRows_rsprogOutput = $query_rsprogOutput->rowCount();
                                                                                    if ($totalRows_rsprogOutput > 0) {
                                                                                        while ($row_rsprogOutput = $query_rsprogOutput->fetch()) {
                                                                                            $program_details_id = $row_rsprogOutput['id'];
                                                                                            $indid = $row_rsprogOutput['indicator'];
                                                                                            $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid =:indid ");
                                                                                            $query_Indicator->execute(array(":indid" => $indid));
                                                                                            $row = $query_Indicator->fetch();
                                                                                            $indname = $row ? $row['indicator_name'] : "";
                                                                                            $selected = $program_details_id == $outputid ? "selected" : "";
                                                                                    ?>
                                                                                            <option value="<?= $program_details_id ?>" <?= $selected ?>><?= $indname ?></option>
                                                                                    <?php
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="indicator[]" id="indicatorrow<?= $rowno ?>" placeholder="Enter" value=" <?= $unit_of_measure . " of " . $indicator_name ?>" class="form-control" disabled />
                                                                            </td>
                                                                            <td>
                                                                                <a type="button" data-toggle="modal" data-target="#outputItemModal" onclick='get_output_details("row<?= $rowno ?>")' id="outputItemModalBtnrow<?= $rowno ?>"> Edit Details</a>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_output("row<?= $rowno ?>")'>
                                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                                </button>
                                                                            </td>
                                                                            <input type="hidden" name="outputIdsTrue[]" id="output_result_id_row<?= $rowno ?>" value="<?php echo $opids ?>" />
                                                                            <input type="hidden" name="output_id[]" id="output_id_row<?= $rowno ?>" value="<?= $outputid ?>" />
                                                                            <input type="hidden" name="indicator[]" id="indicator_id_row<?= $rowno ?>" placeholder="Enter" class="form-control" value="<?= $indicatorID ?>" />
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr id="hideinfo">
                                                                        <td colspan="5" align="center">
                                                                            Add Outputs!!
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                        <input type="hidden" name="progid" id="progid" value="<?= $progid ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if ($projevaluation == 1) {
                                        ?>
                                            <div id="outcome" class="tab-pane fade">
                                                <fieldset class="scheduler-border" style="border-radius:3px">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                        <i class="fa fa-tasks" style="color:#F44336" aria-hidden="true"></i> Outcome Details
                                                    </legend>
                                                    <div class="header row">
                                                        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                                            <button type="button" id="modal_button" class="pull-right btn bg-indigo" data-toggle="modal" id="addOutcomeModalBtn" data-target="#addOutcomeModal" style="margin-top:-10px; margin-bottom:-10px"> <i class="fa fa-plus-square"></i> Add Outcome </button>
                                                        </div>
                                                    </div>
                                                    <div class="body">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover" id="manageOutcomeTable" style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="5%">#</th>
                                                                            <th width="40%">Outcome</th>
                                                                            <th width="15%">Source of Data</th>
                                                                            <th width="18%">Evaluation Frequency</th>
                                                                            <th width="15%">Number of Endline Evaluations</th>
                                                                            <th width="7%">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        <?php
                                        }
                                        if ($projimpact == 1) {
                                        ?>
                                            <div id="impact" class="tab-pane fade">
                                                <fieldset class="scheduler-border" style="border-radius:3px">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                        <i class="fa fa-id-card-o" style="color:#F44336" aria-hidden="true"></i> Impact Details
                                                    </legend>
                                                    <div class="header row">
                                                        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                                            <button type="button" id="modal_button" class="pull-right btn bg-green" data-toggle="modal" id="addImpactModalBtn" data-target="#addImpactModal" style="margin-top:-10px; margin-bottom:-10px"> <i class="fa fa-plus-square"></i> Add Impact</button>
                                                        </div>
                                                    </div>
                                                    <div class="body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover" id="manageImpactTable" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="5%">#</th>
                                                                        <th width="40%">Impact</th>
                                                                        <th width="15%">Source of Data</th>
                                                                        <th width="18%">Evaluation Frequency</th>
                                                                        <th width="15%">Number of Endline Evaluations</th>
                                                                        <th width="7%">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <?php
                                            if (mne_plan()) {
                                                $approve_details = "{
                                                    projid:$projid,
                                                    workflow_stage:$project_stage_id,
                                                    project_name:'$project',
                                                    sub_stage:'$workflow_stage',
                                                }";
                                            ?>
                                                <button type="submit" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Proceed</button>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Start Modal Item Edit -->
            <div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center" id="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <div class="div-result">
                                                <form class="form-horizontal" id="add_output" action="" method="POST">
                                                    <?= csrf_token_html(); ?>
                                                    <br />
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="program_target" class="control-label">Program Target *:</label>
                                                        <div class="form-input">
                                                            <input type="text" name="program_target" id="program_target" placeholder="Enter" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="program_target" class="control-label">Project Output Target *:</label>
                                                        <div class="form-input">
                                                            <input type="number" min="0" name="project_target" id="project_target" onchange="validate_output_target()" onkeyup="validate_output_target()" placeholder="Enter" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div id="output_distribution_div">

                                                    </div>
                                                    <div id="output_target_distribution_div">

                                                    </div>
                                                    <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                                        <label for="" class="control-label">Output Indicator Disaggregation Required? *:</label>
                                                        <div class="form-line">
                                                            <input name="dissaggregation" type="radio" value="1" onchange="show_dissaggregation(1)" id="dissaggregation1" class="with-gap radio-col-green dissaggregation" required="required" />
                                                            <label for="dissaggregation1">YES</label>
                                                            <input name="dissaggregation" type="radio" value="0" onchange="show_dissaggregation(0)" id="dissaggregation2" class="with-gap radio-col-red dissaggregation" required="required" />
                                                            <label for="dissaggregation2">NO</label>
                                                        </div>
                                                    </div>
                                                    <div id="output_dissaggregation_div">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                            <input type="hidden" name="key_unique" id="key_unique" value="<?= $key_unique ?>">
                                                            <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                            <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                            <input type="hidden" name="progid" id="program_id" value="<?= $progid ?>">
                                                            <input type="hidden" name="progid" id="program_id" value="<?= $progid ?>">
                                                            <input type="hidden" name="projduration1" id="projduration1" value="<?= $projduration ?>">
                                                            <input type="hidden" name="indicator_id" id="indicator_id" value="">
                                                            <input type="hidden" name="output_id" id="output_id" value="">
                                                            <input type="hidden" name="rowno" id="rowno" value="">
                                                            <input type="hidden" name="store_data" id="store_data" value="store_data">
                                                            <input type="hidden" name="mapping_type" id="mapping_type" value="">
                                                            <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->
                    </div>
                    <!-- /modal-content -->
                </div>
                <!-- /modal-dailog -->
            </div>
            <!-- End modal -->

            <!-- start outcome modal add edit -->
            <div class="modal fade" id="addOutcomeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title outcomemodaltitle" style="color:#fff" align="center" id="modal-title">Add Outcome Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <div class="div-result">
                                                <form class="form-horizontal" id="addoutcomeform" method="POST" name="addoutcomeform" action="" enctype="multipart/form-data" autocomplete="off">
                                                    <?= csrf_token_html(); ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="control-label" style="color:#0b548f; font-size:16px">Project: <u><?php echo $project; ?></u></label>
                                                    </div>
                                                    <br />
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label for="outcomeIndicator" class="control-label">Outcome *:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="outcome" id="outcome" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label for="outcomeName" class="control-label">Indicator *:</label>
                                                        <div class="form-input">
                                                            <select name="outcomeIndicator" id="outcomeIndicator" onchange="get_outcome_details()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                                <option value="">.... Select from list ....</option>
                                                                <?php
                                                                while ($row_rsOutcomeIndicators = $query_rsOutcomeIndicators->fetch()) {
                                                                    $indicatorId = $row_rsOutcomeIndicators['indid'];
                                                                ?>
                                                                    <option value="<?php echo $indicatorId ?>"><?php echo $row_rsOutcomeIndicators['indicator_name'] ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <label class="control-label">Unit of Measure <span id="impunit"></span>*:</label>
                                                        <div class="form-input">
                                                            <input type="text" name="outcomeunitofmeasure" readonly id="outcomeunitofmeasure" class="form-control" required="required">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <label for="outcom_calc_method" class="control-label">Calculation Method *:</label>
                                                        <div class="form-input">
                                                            <input type="text" name="outcom_calc_method" readonly id="outcom_calc_method" class="form-control" required="required">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="outcomeData" class="control-label">Source of data *:</label>
                                                        <div class="form-input">
                                                            <select name="outcomedataSource" id="outcomedataSource" onchange="add_outcome_questions()" class="form-control" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                                <option value="">.... Select from list ....</option>
                                                                <option value="1">Survey</option>
                                                                <option value="2">Records</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="impactData" class="control-label">Evaluation Frequency*:</label>
                                                        <div class="form-input">
                                                            <select data-id="0" name="outcome_frequency" id="outcome_frequency" class="form-control  selected_outcome_frequency" required="required">
                                                                <?php
                                                                $query_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1 AND level >=4");
                                                                $query_frequency->execute();
                                                                $totalRows_frequency = $query_frequency->rowCount();
                                                                $input = '<option value="">... Select from list ...</option>';
                                                                if ($totalRows_frequency > 0) {
                                                                    while ($row_frequency = $query_frequency->fetch()) {
                                                                        $input .= '<option value="' . $row_frequency['fqid'] . '">' . $row_frequency['frequency'] . ' </option>';
                                                                    }
                                                                } else {
                                                                    $input .= '<option value="">No defined frequency</option>';
                                                                }
                                                                echo $input;
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label class="control-label">Number of Endline Evaluations *:</label>
                                                        <div class="form-line">
                                                            <input type="hidden" name="evaluationNumber" id="evaluationNumber">
                                                            <input type="number" name="evaluationNumberFreq" id="evaluationNumberFreq" onchange="outcome_Evaluation()" onkeyup="outcome_Evaluation()" class="form-control" placeholder="Enter Number of endline evaluations" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                            <input type="hidden" name="addoutcome" id="addoutcome" value="addoutcome">
                                                            <input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
                                                            <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="outcome-tag-form-submit" value="Save" />
                                                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->
                    </div>
                    <!-- /modal-content -->
                </div>
                <!-- /modal-dailog -->
            </div>
            <!-- End Outcome Modal add edit -->

            <!-- start Impact modal add edit -->
            <div class="modal fade" id="addImpactModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title impactmodaltitle" style="color:#fff" align="center" id="modal-title">Add Impact Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <div class="div-result">
                                                <form class="form-horizontal" id="addimpactform" method="POST" name="addimpactform" action="" enctype="multipart/form-data" autocomplete="off">
                                                    <?= csrf_token_html(); ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="control-label" style="color:#0b548f; font-size:16px">Project: <u><?php echo $project; ?></u></label>
                                                    </div>
                                                    <br />
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label for="impact" class="control-label">Impact *:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="impact" id="impact" placeholder="Enter the impact" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label for="impactName" class="control-label">Indicator *:</label>
                                                        <div class="form-input">
                                                            <select name="impactIndicator" id="impactIndicator" onchange="get_impact_details()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                                <option value="">.... Select from list ....</option>
                                                                <?php
                                                                do {
                                                                    $indId = $row_impactIndicators['indid'];
                                                                ?>
                                                                    <option value="<?php echo $indId ?>"><?php echo $row_impactIndicators['indicator_name'] ?></option>
                                                                <?php
                                                                } while ($row_impactIndicators = $query_impactIndicators->fetch());
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <label class="control-label">Unit of Measure <span id="impunit"></span>*:</label>
                                                        <div class="form-input">
                                                            <input type="text" name="impactunitofmeasure" readonly id="impactunitofmeasure" class="form-control" placeholder="Select change" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <label for="impact_calc_method" class="control-label">Calculation Method *:</label>
                                                        <div class="form-input">
                                                            <input type="text" name="impact_calc_method" readonly id="impact_calc_method" class="form-control" placeholder="First select change to be measured" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="impactData" class="control-label">Source of data *:</label>
                                                        <div class="form-input">
                                                            <select name="impactdataSource" id="impactdataSource" onchange="add_impact_questions()" class="form-control" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                                <option value="">.... Select from list ....</option>
                                                                <option value="1">Survey</option>
                                                                <option value="2">Records</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="impactData" class="control-label">Evaluation Frequency*:</label>
                                                        <div class="form-input">
                                                            <select data-id="0" name="impact_frequency" id="impact_frequency" class="form-control  selected_outcome" required="required">
                                                                <?php
                                                                $query_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1 AND level >=4");
                                                                $query_frequency->execute();
                                                                $totalRows_frequency = $query_frequency->rowCount();

                                                                $input = '<option value="">... Select from list ...</option>';
                                                                if ($totalRows_frequency > 0) {
                                                                    while ($row_frequency = $query_frequency->fetch()) {
                                                                        $input .= '<option value="' . $row_frequency['fqid'] . '">' . $row_frequency['frequency'] . ' </option>';
                                                                    }
                                                                } else {
                                                                    $input .= '<option value="">No defined frequency</option>';
                                                                }
                                                                echo $input;
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label class="control-label">Number of Endline Evaluations *:</label>
                                                        <div class="form-line">
                                                            <input type="hidden" name="evaluationNumber" id="evaluationNumber">
                                                            <input type="number" name="evaluationNumberFreq" id="evaluationNumberFreq" onchange="outcome_Evaluation()" onkeyup="outcome_Evaluation()" class="form-control" placeholder="Enter Number of endline evaluations" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                            <input type="hidden" name="addimpact" id="addimpact" value="addimpact">
                                                            <input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
                                                            <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="impact-tag-form-submit" value="Save" />
                                                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->
                    </div>
                    <!-- /modal-content -->
                </div>
                <!-- /modal-dailog -->
            </div>
            <!-- End Impact Modal add edit -->
<?php
        } else {
            $results =  restriction();
            echo $results;
        }
    } catch (PDOException $ex) {
        customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
    }
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script src="assets/js/mneplan/add-project-mne-plan.js"></script>
<script src="assets/js/mneplan/index.js"></script>
<script src="assets/js/projects/output.js"></script>
<script src="assets/js/master/index.js"></script>