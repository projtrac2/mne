<?php
try {
    include '../controller.php';
    function get_status($status_id)
    {
        global $db;
        $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :status_id");
        $query_Projstatus->execute(array(":status_id" => $status_id));
        $row_Projstatus = $query_Projstatus->fetch();
        $total_Projstatus = $query_Projstatus->rowCount();
        $status = "";
        if ($total_Projstatus > 0) {
            $status_name = $row_Projstatus['statusname'];
            $status_class = $row_Projstatus['class_name'];
            $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
        }
        return $status;
    }

    function calculate_site_progress($implimentation_type, $site_id)
    {
        global $db;
        $direct_cost = 0;
        if ($implimentation_type == 1) {
            $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE cost_type=1  AND site_id=:site_id");
            $query_rsOther_cost_plan_budget->execute(array(':site_id' => $site_id));
            $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
            $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
        } else {
            $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE site_id=:site_id");
            $query_rsOther_cost_plan_budget->execute(array(':site_id' => $site_id));
            $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
            $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
        }

        $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.site_id=:site_id");
        $query_rsPercentage->execute(array(':site_id' => $site_id));
        $progress = 0;
        while ($row_rsPercentage = $query_rsPercentage->fetch()) {
            $subtask_id = $row_rsPercentage['subtask_id'];
            $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
            $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;

            if ($percentage >= 100) {
                $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id  AND complete=1 AND site_id=:site_id");
                $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id, ':site_id' => $site_id));
                $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                $percentage = $totalRows_rsTask_Start_Dates > 1 ? 100 : 99;
            }
            $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
        }

        return $progress;
    }

    function calculate_output_site_progress($output_id, $implimentation_type, $site_id)
    {
        global $db;
        $direct_cost = 0;
        if ($implimentation_type == 1) {
            $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE outputid =:output_id AND cost_type=1  AND site_id=:site_id");
            $query_rsOther_cost_plan_budget->execute(array(":output_id" => $output_id, ':site_id' => $site_id));
            $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
            $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
        } else {
            $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE outputid =:output_id  AND site_id=:site_id");
            $query_rsOther_cost_plan_budget->execute(array(":output_id" => $output_id, ':site_id' => $site_id));
            $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
            $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
        }

        $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.outputid =:output_id AND d.site_id=:site_id");
        $query_rsPercentage->execute(array(":output_id" => $output_id, ':site_id' => $site_id));
        $progress = 0;
        while ($row_rsPercentage = $query_rsPercentage->fetch()) {
            $subtask_id = $row_rsPercentage['subtask_id'];
            $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
            $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;

            if ($percentage >= 100) {
                $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE subtask_id=:subtask_id  AND complete=1 AND site_id=:site_id");
                $query_rsTask_Start_Dates->execute(array(':subtask_id' => $subtask_id, ':site_id' => $site_id));
                $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                $percentage = $totalRows_rsTask_Start_Dates > 1 ? 100 : 99;
            }
            $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
        }

        return $progress;
    }

    function get_validate_site($site_id, $start_date, $end_date)
    {
        global $db;
        $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where site_id=:site_id");
        $query_Site_score->execute(array(":site_id" => $site_id));
        if ($start_date != '' && $end_date != '') {
            $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND created_at <=:end_date AND site_id=:site_id");
            $query_Site_score->execute(array(":start_date" => $start_date, ":end_date" => $end_date, ":site_id" => $site_id));
        } else if ($start_date != '' && $end_date == '') {
            $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND site_id=:site_id");
            $query_Site_score->execute(array(":start_date" => $start_date, ":site_id" => $site_id));
        } else if ($start_date == '' && $end_date != '') {
            $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where  created_at <=:end_date AND site_id=:site_id");
            $query_Site_score->execute(array(":end_date" => $end_date, ":site_id" => $site_id));
        }
        $row_site_score = $query_Site_score->fetch();
        return $row_site_score ? true : false;
    }

    function get_validate_output($site_id, $output_id, $start_date, $end_date)
    {
        global $db;
        $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where site_id=:site_id AND output_id=:output_id");
        $query_Site_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id,));
        if ($start_date != '' && $end_date != '') {
            $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND created_at <=:end_date AND site_id=:site_id AND output_id=:output_id");
            $query_Site_score->execute(array(":start_date" => $start_date, ":site_id" => $site_id, ":output_id" => $output_id,));
        } else if ($start_date != '' && $end_date == '') {
            $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at >=:start_date  AND site_id=:site_id AND output_id=:output_id");
            $query_Site_score->execute(array(":start_date" => $start_date, ":site_id" => $site_id, ":output_id" => $output_id,));
        } else if ($start_date == '' && $end_date != '') {
            $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where  created_at <=:end_date AND site_id=:site_id AND output_id=:output_id");
            $query_Site_score->execute(array(":end_date" => $end_date, ":site_id" => $site_id, ":output_id" => $output_id,));
        }
        $row_site_score = $query_Site_score->fetch();
        return $row_site_score ? true : false;
    }

    function get_validate_subtask($site_id, $output_id, $subtask_id, $start_date, $end_date)
    {
        global $db;
        $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
        $query_Site_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $subtask_id));
        if ($start_date != '' && $end_date != '') {
            $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND created_at <=:end_date AND site_id=:site_id AND output_id=:output_id  AND subtask_id=:subtask_id");
            $query_Site_score->execute(array(":start_date" => $start_date, ":end_date" => $end_date, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $subtask_id));
        } else if ($start_date != '' && $end_date == '') {
            $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND site_id=:site_id AND output_id=:output_id  AND subtask_id=:subtask_id");
            $query_Site_score->execute(array(":start_date" => $start_date, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $subtask_id));
        } else if ($start_date == '' && $end_date != '') {
            $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at <=:end_date AND site_id=:site_id AND output_id=:output_id  AND subtask_id=:subtask_id");
            $query_Site_score->execute(array(":end_date" => $end_date, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $subtask_id));
        }
        $row_site_score = $query_Site_score->fetch();
        return !is_null($row_site_score['achieved']) ? $row_site_score['achieved'] : false;
    }

    function get_report_progress($progress, $projstatus)
    {
        $css_class = "progress-bar progress-bar-info progress-bar-striped active";
        $progress_bar = $progress;
        if ($progress == 100 && $projstatus == 5) {
            $css_class = "progress-bar progress-bar-success progress-bar-striped active";
            $progress_bar = 100;
        } else if ($progress > 100) {
            if ($projstatus == 5) {
                $css_class = "progress-bar progress-bar-success progress-bar-striped active";
                $progress_bar = 100;
            } else {
                $css_class = "progress-bar progress-bar-info progress-bar-striped active";
                $progress_bar = 100;
            }
        } else if ($progress <  100 && $projstatus == 5) {
            $css_class = "progress-bar progress-bar-success progress-bar-striped active";
            $progress_bar = 100;
        }

        return  '
    <div class="progress" style="height:20px; font-size:10px; color:black">
        <div class="' . $css_class . '" role="progressbar" aria-valuenow="' . $progress_bar . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress_bar . '%; height:20px; font-size:10px; color:black">
            ' . number_format($progress, 2) . '%
        </div>
    </div>';
    }

    function get_measurement_unit($unit)
    {
        global $db;
        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
        $query_rsIndUnit->execute(array(":unit_id" => $unit));
        $row_rsIndUnit = $query_rsIndUnit->fetch();
        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
        return $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';
    }

    function get_target_units($site_id, $subtask_id)
    {
        global $db;
        $query_rsOther_cost_plan_budget =  $db->prepare("SELECT units_no FROM tbl_project_direct_cost_plan WHERE site_id=:site_id AND subtask_id=:subtask_id");
        $query_rsOther_cost_plan_budget->execute(array(":site_id" => $site_id, ":subtask_id" => $subtask_id));
        $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
        $planned_units = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['units_no'] : 0;

        $query_rsAdjustments = $db->prepare("SELECT SUM(units) as units FROM tbl_project_adjustments where site_id=:site_id AND sub_task_id=:subtask_id");
        $query_rsAdjustments->execute(array(":site_id" => $site_id, ":subtask_id" => $subtask_id));
        $row_rsAdjustments = $query_rsAdjustments->fetch();
        $adjusted_units = ($row_rsAdjustments['units'] != null) ? $row_rsAdjustments['units'] : 0;
        return $planned_units + $adjusted_units;
    }



    if (isset($_GET['get_filter_record'])) {
        $projid = $_GET['projid'];
        $implimentation_type = $_GET['implimentation_type'];
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : "";
        $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ?  $_GET['end_date'] : '';

        $project_details = '
        <div class="row clearfix" id="filter_data">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
        $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
        $query_Sites->execute(array(":projid" => $projid));
        $rows_sites = $query_Sites->rowCount();
        if ($rows_sites > 0) {
            $counter = 0;
            while ($row_Sites = $query_Sites->fetch()) {
                $site_id = $row_Sites['site_id'];
                $site = $row_Sites['site'];
                if (get_validate_site($site_id, $start_date, $end_date)) {
                    $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id");
                    $query_Site_score->execute(array(":site_id" => $site_id));
                    $rows_site_score = $query_Site_score->rowCount();
                    if ($rows_site_score > 0) {
                        $counter++;

                        $query_rsProgramOfWorks =  $db->prepare("SELECT * FROM tbl_program_of_works WHERE site_id=:site_id AND complete=0 ");
                        $query_rsProgramOfWorks->execute(array(":site_id" => $site_id));
                        $row_rsProgramOfWorks = $query_rsProgramOfWorks->rowCount();
                        $site_status = ($row_rsProgramOfWorks > 0) ? 4 : 5;
                        $progress = number_format(calculate_site_progress($implimentation_type, $site_id), 2);
                        $site_progress = get_report_progress($progress, $site_status);

                        $project_details .= '
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                <i class="fa fa-list-ol" aria-hidden="true"></i> Site ' . $counter . ' :
                            </legend>
                            <div class="card-header">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item list-group-item-action active">Site : ' . $site . '</li>
                                            <li class="list-group-item">Progress : ' . $site_progress . '</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>';

                        $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
                        $query_Site_Output->execute(array(":site_id" => $site_id));
                        $rows_Site_Output = $query_Site_Output->rowCount();
                        if ($rows_Site_Output > 0) {
                            $output_counter = 0;
                            while ($row_Site_Output = $query_Site_Output->fetch()) {
                                $output_counter++;
                                $output_id = $row_Site_Output['outputid'];
                                if (get_validate_output($site_id, $output_id, $start_date, $end_date)) {
                                    $query_output_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND output_id=:output_id");
                                    $query_output_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
                                    $rows_output_score = $query_output_score->rowCount();
                                    if ($rows_output_score > 0) {
                                        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                                        $query_Output->execute(array(":outputid" => $output_id));
                                        $row_Output = $query_Output->fetch();
                                        $total_Output = $query_Output->rowCount();
                                        if ($total_Output) {
                                            $output_id = $row_Output['id'];
                                            $output = $row_Output['indicator_name'];
                                            $output_status = $row_Output['complete'] == 1 ? 5 : 4;
                                            $progress = number_format(calculate_output_site_progress($output_id, $implimentation_type, $site_id), 2);
                                            $output_progress = get_report_progress($progress, $output_status);
                                            $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
                                            $query_rsTargetUsed->execute(array(":output_id" => $output_id));
                                            $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                                            $output_achieved = $Rows_rsTargetUsed['achieved'] != null ? $Rows_rsTargetUsed['achieved'] : 0;
                                            $project_details .= '
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                    <i class="fa fa-list-ol" aria-hidden="true"></i> Output ' . $counter . ' :
                                                </legend>
                                                <div class="row clearfix">
                                                    <div class="card-header">
                                                        <div class="row clearfix">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <ul class="list-group">
                                                                    <li class="list-group-item list-group-item list-group-item-action active">Output : ' . $output . '</li>
                                                                    <li class="list-group-item">Achieved : ' . number_format($output_achieved, 2) . '</li>
                                                                    <li class="list-group-item">Progress : ' . $output_progress . '</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table' . $output_id . '">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width:5%">#</th>
                                                                        <th style="width:30%">Item</th>
                                                                        <th style="width:15%">Target</th>
                                                                        <th style="width:20%">Achieved</th>
                                                                        <th style="width:10%">Status</th>
                                                                        <th style="width:15%">%&nbsp;&nbsp;Achieved</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';

                                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid");
                                            $query_rsTasks->execute(array(":output_id" => $output_id));
                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                            if ($totalRows_rsTasks > 0) {
                                                $tcounter = 0;
                                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                    $task_name = $row_rsTasks['task'];
                                                    $task_id = $row_rsTasks['tkid'];
                                                    $achieved = get_validate_subtask($site_id, $output_id, $task_id, $start_date, $end_date);
                                                    if ($achieved) {
                                                        $unit =  $row_rsTasks['unit_of_measure'];
                                                        $query_rsProgramOfWorks =  $db->prepare("SELECT * FROM tbl_program_of_works WHERE site_id=:site_id AND subtask_id=:subtask_id ");
                                                        $query_rsProgramOfWorks->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
                                                        $row_rsProgramOfWorks = $query_rsProgramOfWorks->fetch();

                                                        if ($row_rsProgramOfWorks > 0) {
                                                            $tcounter++;
                                                            $subtask_status = $row_rsProgramOfWorks['status'];
                                                            $complete = $row_rsProgramOfWorks['complete'];
                                                            $sub_status = $complete == 1 ? 5 : 4;
                                                            $target_units = get_target_units($site_id, $task_id);
                                                            $progress = $achieved > 0 && $target_units > 0 ? ($achieved / $target_units) * 100 : 0;
                                                            $subtask_progress = get_report_progress($progress, $sub_status);
                                                            $status = get_status($subtask_status);
                                                            $unit_of_measure = get_measurement_unit($unit);

                                                            $project_details .= '
                                                            <tr id="row' . $tcounter . '">
                                                                <td style="width:5%">' . $tcounter . '</td>
                                                                <td style="width:35%">' . $task_name . '</td>
                                                                <td style="width:15%">' . number_format($target_units, 2) . " " . $unit_of_measure  . '</td>
                                                                <td style="width:20%">' . number_format($achieved, 2) . " " . $unit_of_measure . '</td>
                                                                <td style="width:10%">' . $status . '</td>
                                                                <td style="width:10%">' . $subtask_progress . '</td>
                                                            </tr>';
                                                        }
                                                    }
                                                }
                                            }
                                            $project_details .= '
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        </fieldset>';
                                        }
                                    }
                                }
                            }
                        }
                        $project_details .= '
                        </fieldset>';
                    }
                }
            }
        }

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1 AND projid = :projid");
        $query_Output->execute(array(":projid" => $projid));
        $total_Output = $query_Output->rowCount();
        $outputs = '';
        if ($total_Output > 0) {
            $outputs = '';
            if ($total_Output > 0) {
                $counter = 0;
                $site_id = 0;
                while ($row_rsOutput = $query_Output->fetch()) {
                    $output_id = $row_rsOutput['id'];
                    $output = $row_rsOutput['indicator_name'];
                    if (get_validate_output($site_id, $output_id, $start_date, $end_date)) {
                        $query_output_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND output_id=:output_id");
                        $query_output_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
                        $rows_output_score = $query_output_score->rowCount();
                        if ($rows_output_score > 0) {
                            $counter++;
                            $output_status = $row_rsOutput['complete'] == 1 ? 5 : 4;
                            $progress = number_format(calculate_output_site_progress($output_id, $implimentation_type, $site_id), 2);
                            $output_progress = get_report_progress($progress, $output_status);

                            $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
                            $query_rsTargetUsed->execute(array(":output_id" => $output_id));
                            $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                            $output_achieved = $Rows_rsTargetUsed['achieved'] != null ? $Rows_rsTargetUsed['achieved'] : 0;
                            $project_details .= '
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-list-ol" aria-hidden="true"></i> Output ' . $counter . ' : ' . $output . '
                                </legend>
                                <div class="card-header">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="list-group">
                                                <li class="list-group-item list-group-item list-group-item-action active">Output : ' . $output . '</li>
                                                <li class="list-group-item">Achieved : ' . number_format($output_achieved, 2) . '</li>
                                                <li class="list-group-item">Progress : ' . $output_progress . '</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table' . $output_id . '">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th style="width:35%">Item</th>
                                                <th style="width:15%">Target</th>
                                                <th style="width:20%">Achieved</th>
                                                <th style="width:10%">Status</th>
                                                <th style="width:10%">%&nbsp;&nbsp;Achieved</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id ORDER BY tkid");
                            $query_rsTasks->execute(array(":output_id" => $output_id));
                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                            if ($totalRows_rsTasks > 0) {
                                $tcounter = 0;
                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                    $task_name = $row_rsTasks['task'];
                                    $task_id = $row_rsTasks['tkid'];
                                    $unit =  $row_rsTasks['unit_of_measure'];
                                    $achieved = get_validate_subtask($site_id, $output_id, $task_id, $start_date, $end_date);
                                    if ($achieved) {
                                        $query_rsProgramOfWorks =  $db->prepare("SELECT * FROM tbl_program_of_works WHERE site_id=:site_id AND subtask_id=:subtask_id ");
                                        $query_rsProgramOfWorks->execute(array(":site_id" => $site_id, ":subtask_id" => $task_id));
                                        $row_rsProgramOfWorks = $query_rsProgramOfWorks->fetch();

                                        if ($row_rsProgramOfWorks > 0) {
                                            $tcounter++;
                                            $subtask_status = $row_rsProgramOfWorks['status'];
                                            $complete = $row_rsProgramOfWorks['complete'];
                                            $sub_status = $complete == 1 ? 5 : 4;
                                            $target_units = get_target_units($site_id, $task_id);
                                            $progress = $achieved > 0 && $target_units > 0 ? ($achieved / $target_units) * 100 : 0;
                                            $subtask_progress = get_report_progress($progress, $sub_status);
                                            $status = get_status($subtask_status);
                                            $unit_of_measure = get_measurement_unit($unit);

                                            $project_details .= '
                                            <tr id="row' . $tcounter . '">
                                                <td style="width:5%">' . $tcounter . '</td>
                                                <td style="width:35%">' . $task_name . '</td>
                                                <td style="width:15%">' . number_format($target_units, 2) . " " . $unit_of_measure  . '</td>
                                                <td style="width:20%">' . number_format($achieved, 2) . " " . $unit_of_measure . '</td>
                                                <td style="width:10%">' . $status . '</td>
                                                <td style="width:10%">' . $subtask_progress . '</td>
                                            </tr>';
                                        }
                                    }
                                }
                            }
                            $project_details .= '
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>';
                        }
                    }
                }
            }
        }
        $project_details .= '
            </div>
        </div>';

        echo json_encode(array("success" => true, "data" => $project_details));
    }
} catch (PDOException $ex) {
    var_dump($ex);
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
