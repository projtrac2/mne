<?php
try {
    include '../controller.php';


    function calculate_output_site_progress($output_id, $implimentation_type, $site_id, $start_date, $end_date)
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

        if ($start_date != '' && $end_date != '') {
            $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.outputid =:output_id AND d.site_id=:site_id AND created_at BETWEEN :start_date AND :end_date");
            $query_rsPercentage->execute(array(":output_id" => $output_id, ':site_id' => $site_id, ":start_date" => $start_date, ":end_date" => $end_date));
            $progress = 0;
            while ($row_rsPercentage = $query_rsPercentage->fetch()) {
                $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
                $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;
                $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
            }
        } else if ($start_date != '' && $end_date == '') {
            $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.outputid =:output_id AND d.site_id=:site_id AND created_at >= :start_date");
            $query_rsPercentage->execute(array(":output_id" => $output_id, ':site_id' => $site_id, ":start_date" => $start_date));
            $progress = 0;
            while ($row_rsPercentage = $query_rsPercentage->fetch()) {
                $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
                $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;
                $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
            }
        }

        return $progress;
    }

    function calculate_site_progress($implimentation_type, $site_id, $start_date, $end_date)
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

        if ($start_date != '' && $end_date != '') {
            $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.site_id=:site_id AND created_at BETWEEN :start_date AND :end_date ");
            $query_rsPercentage->execute(array(':site_id' => $site_id, ":start_date" => $start_date, ":end_date" => $end_date));
            $progress = 0;
            while ($row_rsPercentage = $query_rsPercentage->fetch()) {
                $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
                $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;
                $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
            }
        } else if ($start_date != '' && $end_date == '') {
            $query_rsPercentage =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE d.site_id=:site_id AND created_at >= :start_date");
            $query_rsPercentage->execute(array(':site_id' => $site_id, ":start_date" => $start_date));
            $progress = 0;
            while ($row_rsPercentage = $query_rsPercentage->fetch()) {
                $cost =   $row_rsPercentage['unit_cost'] * $row_rsPercentage['units_no'];
                $percentage =   ($row_rsPercentage['achieved'] / $row_rsPercentage['units_no']) * 100;
                $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
            }
        }
        return $progress;
    }

    function check_data_checklist_score($origin, $start_date, $end_date, $site_id, $output_id)
    {
        global $db;
        $rows_site_score = 0;
        if ($origin == 1) {
            if ($start_date != '' && $end_date != '') {
                $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND created_at <=:end_date AND site_id=:site_id");
                $query_Site_score->execute(array(":start_date" => $start_date, ":end_date" => $end_date, ":site_id" => $site_id));
                $rows_site_score = $query_Site_score->rowCount();
            } else if ($start_date != '' && $end_date == '') {
                $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND site_id=:site_id");
                $query_Site_score->execute(array(":start_date" => $start_date, ":site_id" => $site_id));
                $rows_site_score = $query_Site_score->rowCount();
            }
        } else if ($origin == 2) {
            if ($start_date != '' && $end_date != '') {
                $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND created_at <=:end_date AND site_id=:site_id AND output_id=:output_id");
                $query_Site_score->execute(array(":start_date" => $start_date, ":end_date" => $end_date, ":site_id" => $site_id, ":output_id" => $output_id));
                $rows_site_score = $query_Site_score->rowCount();
            } else if ($start_date != '' && $end_date == '') {
                $query_Site_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score where created_at >= :start_date AND site_id=:site_id AND output_id=:output_id");
                $query_Site_score->execute(array(":start_date" => $start_date, ":site_id" => $site_id, ":output_id" => $output_id));
                $rows_site_score = $query_Site_score->rowCount();
            } else {
                $query_output_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND output_id=:output_id");
                $query_output_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
                $rows_site_score = $query_output_score->rowCount();
            }
        }
        return $rows_site_score;
    }

    if (isset($_GET['get_filter_record'])) {
        $projid = $_GET['projid'];
        $implimentation_type = $_GET['implimentation_type'];
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : "";
        $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ?  $_GET['end_date'] : '';
        $success = true;
        $data = '
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
        $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
        $query_Sites->execute(array(":projid" => $projid));
        $rows_sites = $query_Sites->rowCount();
        if ($rows_sites > 0) {
            $counter = 0;
            while ($row_Sites = $query_Sites->fetch()) {
                $site_id = $row_Sites['site_id'];
                $site = $row_Sites['site'];

                $site_score = check_data_checklist_score(1, $start_date, $end_date, $site_id, '');
                if ($site_score > 0) {
                    $counter++;
                    $progress = number_format(calculate_site_progress($implimentation_type, $site_id, $start_date, $end_date), 2);
                    $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE site_id=:site_id");
                    $query_rsTargetUsed->execute(array(":site_id" => $site_id));
                    $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                    $site_achieved = $Rows_rsTargetUsed['achieved'] != null ? $Rows_rsTargetUsed['achieved'] : 0;

                    $site_progress = '
                    <div class="progress" style="height:20px; font-size:10px; color:black">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                            ' . $progress . '%
                        </div>
                    </div>';

                    if ($progress == 100) {
                        $site_progress = '
                        <div class="progress" style="height:20px; font-size:10px; color:black">
                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                            ' . $progress . '%
                            </div>
                        </div>';
                    }

                    $data .= '
                    <fieldset class="scheduler-border">
						<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                        <i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $counter ?> :
                    </legend>
                    <div class="card-header">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active">Site : ' . $site . '</li>
                                    <li class="list-group-item">Achieved : ' . number_format($site_achieved, 2) . '</li>
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
                            $output_score = check_data_checklist_score(2, $start_date, $end_date, $site_id, $output_id);
                            if ($output_score > 0) {
                                $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                                $query_Output->execute(array(":outputid" => $output_id));
                                $row_Output = $query_Output->fetch();
                                $total_Output = $query_Output->rowCount();
                                if ($total_Output) {
                                    $output_id = $row_Output['id'];
                                    $output = $row_Output['indicator_name'];
                                    $progress = number_format(calculate_output_site_progress($output_id, $implimentation_type, $site_id, $start_date, $end_date), 2);
                                    $output_progress = '
                                    <div class="progress" style="height:20px; font-size:10px; color:black">
                                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                            ' . $progress . '%
                                        </div>
                                    </div>';

                                    if ($progress == 100) {
                                        $output_progress = '
                                        <div class="progress" style="height:20px; font-size:10px; color:black">
                                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                            ' . $progress . '%
                                            </div>
                                        </div>';
                                    }
                                    $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
                                    $query_rsTargetUsed->execute(array(":output_id" => $output_id));
                                    $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                                    $output_achieved = $Rows_rsTargetUsed['achieved'] != null ? $Rows_rsTargetUsed['achieved'] : 0;
                                    $data .= '
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-list-ol" aria-hidden="true"></i> Output  ' . $counter . ':
                                        </legend>
                                        <div class="row clearfix">
                                            <div class="card-header">
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <ul class="list-group">
                                                            <li class="list-group-item list-group-item list-group-item-action active">Output :  ' . $output . '</li>
                                                            <li class="list-group-item">Achieved :  ' . number_format($output_achieved, 2) . '</li>
                                                            <li class="list-group-item">Progress :  ' . $output_progress . '</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:5%">#</th>
                                                                <th style="width:40%">Item</th>
                                                                <th style="width:25%">Achieved</th>
                                                                <th style="width:10%">Unit Cost (Ksh)</th>
                                                                <th style="width:10%">Total Cost (Ksh)</th>
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

                                            $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where  site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
                                            $query_Site_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
                                            $row_site_score = $query_Site_score->fetch();
                                            $progress = 0;
                                            if ($start_date  != '' && $end_date != '') {
                                                $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND created_at <=:end_date AND site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
                                                $query_Site_score->execute(array(":start_date" => $start_date, ":end_date" => $end_date, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
                                                $row_site_score = $query_Site_score->fetch();

                                                $query_rsPercentage =  $db->prepare("SELECT (s.achieved/d.units_no)  * 100 as percentage FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE s.created_at >=:start_date AND s.created_at <=:end_date  d.subtask_id =:subtask_id AND s.site_id=:site_id");
                                                $query_rsPercentage->execute(array(":start_date" => $start_date, ":end_date" => $end_date, ":subtask_id" => $subtask_id, ':site_id' => $site_id));
                                                $row_rsPercentage = $query_rsPercentage->fetch();
                                                $progress =   $row_rsPercentage['percentage'] != null ? $row_rsPercentage['percentage'] : 0;
                                            } else  if ($start_date  != '' && $end_date == '') {
                                                $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
                                                $query_Site_score->execute(array(":start_date" => $start_date, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
                                                $row_site_score = $query_Site_score->fetch();

                                                $query_rsPercentage =  $db->prepare("SELECT (s.achieved/d.units_no)  * 100 as percentage FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE s.created_at >=:start_date AND d.subtask_id =:subtask_id AND s.site_id=:site_id");
                                                $query_rsPercentage->execute(array(":start_date" => $start_date, ":subtask_id" => $subtask_id, ':site_id' => $site_id));
                                                $row_rsPercentage = $query_rsPercentage->fetch();
                                                $progress =   $row_rsPercentage['percentage'] != null ? $row_rsPercentage['percentage'] : 0;
                                            }


                                            if ($row_site_score['achieved'] != null) {
                                                $units_no =  $row_site_score['achieved'];
                                                $tcounter++;
                                                $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                $subtask_progress = '
                                                <div class="progress" style="height:20px; font-size:10px; color:black">
                                                    <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                                        ' . $progress . '%
                                                    </div>
                                                </div>';

                                                if ($progress == 100) {
                                                    $subtask_progress = '
                                                    <div class="progress" style="height:20px; font-size:10px; color:black">
                                                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                                        ' . $progress . '%
                                                        </div>
                                                    </div>';
                                                }

                                                $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                                                $query_Projstatus->execute(array(":projstatus" => 11));
                                                $row_Projstatus = $query_Projstatus->fetch();
                                                $total_Projstatus = $query_Projstatus->rowCount();
                                                $status = "";
                                                if ($total_Projstatus > 0) {
                                                    $status_name = $row_Projstatus['statusname'];
                                                    $status_class = $row_Projstatus['class_name'];
                                                    $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
                                                }
                                                $data .= '
                                                <tr id="row' . $tcounter . '">
                                                <td style="width:5%">' . $tcounter . '</td>
                                                <td style="width:50%">' . $task_name . '</td>
                                                <td style="width:25%">' . number_format($units_no) . " " . $unit_of_measure . '</td>
                                                <td style="width:10%">' . $status . '</td>
                                                <td style="width:10%">' . $subtask_progress . '</td>
                                            </tr>';
                                            }
                                        }
                                    }
                                    $data .= '
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
                    $data .= '</fieldset>';
                }
            }
        }


        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type=2 AND projid = :projid");
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

                    $query_output_score = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND output_id=:output_id");
                    $query_output_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
                    $rows_output_score = $query_output_score->rowCount();
                    if ($rows_output_score > 0) {
                        $counter++;
                        $output_progress = '
                        <div class="progress" style="height:20px; font-size:10px; color:black">
                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                ' . $progress . '%
                            </div>
                        </div>';

                        if ($progress == 100) {
                            $output_progress = '
                            <div class="progress" style="height:20px; font-size:10px; color:black">
                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                ' . $progress . '%
                                </div>
                            </div>';
                        }

                        $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                        $query_Projstatus->execute(array(":projstatus" => 11));
                        $row_Projstatus = $query_Projstatus->fetch();
                        $total_Projstatus = $query_Projstatus->rowCount();
                        $status = "";
                        if ($total_Projstatus > 0) {
                            $status_name = $row_Projstatus['statusname'];
                            $status_class = $row_Projstatus['class_name'];
                            $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
                        }

                        $query_rsTargetUsed = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_monitoringoutput WHERE output_id=:output_id");
                        $query_rsTargetUsed->execute(array(":output_id" => $output_id));
                        $Rows_rsTargetUsed = $query_rsTargetUsed->fetch();
                        $output_achieved = $Rows_rsTargetUsed['achieved'] != null ? $Rows_rsTargetUsed['achieved'] : 0;
                        $data .= '
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
                            </legend>
                            <div class="card-header">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item list-group-item-action active">Output : <?= $output ?></li>
                                            <li class="list-group-item">Achieved : <?= number_format($output_achieved, 2) ?></li>
                                            <li class="list-group-item">Progress : <?= $output_progress ?></li>
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
                                            <th style="width:25%">Achieved</th>
                                            <th style="width:10%">Unit Cost (Ksh)</th>
                                            <th style="width:10%">Total Cost (Ksh)</th>
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

                                $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where  site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
                                $query_Site_score->execute(array(":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
                                $row_site_score = $query_Site_score->fetch();
                                $progress = 0;
                                if ($start_date  != '' && $end_date != '') {
                                    $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND created_at <=:end_date AND site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
                                    $query_Site_score->execute(array(":start_date" => $start_date, ":end_date" => $end_date, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
                                    $row_site_score = $query_Site_score->fetch();

                                    $query_rsPercentage =  $db->prepare("SELECT (s.achieved/d.units_no)  * 100 as percentage FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE s.created_at >=:start_date AND s.created_at <=:end_date  d.subtask_id =:subtask_id AND s.site_id=:site_id");
                                    $query_rsPercentage->execute(array(":start_date" => $start_date, ":end_date" => $end_date, ":subtask_id" => $subtask_id, ':site_id' => $site_id));
                                    $row_rsPercentage = $query_rsPercentage->fetch();
                                    $progress =   $row_rsPercentage['percentage'] != null ? $row_rsPercentage['percentage'] : 0;
                                } else  if ($start_date  != '' && $end_date == '') {
                                    $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where created_at >=:start_date AND site_id=:site_id AND output_id=:output_id AND subtask_id=:subtask_id");
                                    $query_Site_score->execute(array(":start_date" => $start_date, ":site_id" => $site_id, ":output_id" => $output_id, ":subtask_id" => $task_id));
                                    $row_site_score = $query_Site_score->fetch();

                                    $query_rsPercentage =  $db->prepare("SELECT (s.achieved/d.units_no)  * 100 as percentage FROM tbl_project_direct_cost_plan d INNER JOIN tbl_project_monitoring_checklist_score s ON s.subtask_id = d.subtask_id WHERE s.created_at >=:start_date AND d.subtask_id =:subtask_id AND s.site_id=:site_id");
                                    $query_rsPercentage->execute(array(":start_date" => $start_date, ":subtask_id" => $subtask_id, ':site_id' => $site_id));
                                    $row_rsPercentage = $query_rsPercentage->fetch();
                                    $progress =   $row_rsPercentage['percentage'] != null ? $row_rsPercentage['percentage'] : 0;
                                }

                                if ($row_site_score['achieved'] != null) {
                                    $units_no =  $row_site_score['achieved'];
                                    $tcounter++;
                                    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                    $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                    $row_rsIndUnit = $query_rsIndUnit->fetch();
                                    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                    $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                    $subtask_progress = '
                                    <div class="progress" style="height:20px; font-size:10px; color:black">
                                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                            ' . $progress . '%
                                        </div>
                                    </div>';

                                    if ($progress == 100) {
                                        $subtask_progress = '
                                        <div class="progress" style="height:20px; font-size:10px; color:black">
                                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                            ' . $progress . '%
                                            </div>
                                        </div>';
                                    }

                                    $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                                    $query_Projstatus->execute(array(":projstatus" => 11));
                                    $row_Projstatus = $query_Projstatus->fetch();
                                    $total_Projstatus = $query_Projstatus->rowCount();
                                    $status = "";
                                    if ($total_Projstatus > 0) {
                                        $status_name = $row_Projstatus['statusname'];
                                        $status_class = $row_Projstatus['class_name'];
                                        $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
                                    }

                                    $data .= '
                                                <tr id="row' . $tcounter . '">
                                                <td style="width:5%">' . $tcounter . '</td>
                                                <td style="width:50%">' . $task_name . '</td>
                                                <td style="width:25%">' . number_format($units_no) . " " . $unit_of_measure . '</td>
                                                <td style="width:10%">' . $status . '</td>
                                                <td style="width:10%">' . $subtask_progress . '</td>
                                            </tr>';
                                }
                            }
                        }
                        $data .= '
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>';
                    }
                }
            }
        }

        $data .= '</div>';
        echo json_encode(array("success" => $success, "data" => $data));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
