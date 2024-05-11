<?php
try {
    include '../controller.php';

    function get_sections($sector_id)
    {
        global $db;
        $query_rsSector = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE deleted='0' and stid=:sector_id");
        $query_rsSector->execute(array(":sector_id" => $sector_id));
        $row_rsSector = $query_rsSector->fetch();
        return $row_rsSector ? $row_rsSector['sector'] : "";
    }

    if (isset($_GET['program_info'])) {
        $progid = $_GET['progid'];
        $strategic_plan_id = $_GET['strategic_plan_id'];
        $query_item = $db->prepare("SELECT * FROM tbl_programs  WHERE progid = :progid ");
        $query_item->execute(array(":progid" => $progid));
        $row_item = $query_item->fetch();
        $rows_count = $query_item->rowCount();
        $input = '';
        $output_table_header = $output_table_header_rowspan = $output_table_body = '';
        $strategic_plan = $objective = $strategy = 'N/A';

        if ($rows_count > 0) {
            $program_name = $row_item['progname'];
            $program_problem_statement = $row_item['problem_statement'];
            $description = $row_item['description'];
            $department_id = $row_item['projsector'];
            $sector_id = $row_item['projdept'];
            $directorate = $row_item['directorate'];
            $program_department = get_sections($department_id);
            $program_section = get_sections($sector_id);
            $program_directorate = get_sections($directorate);
            $strategic_plan = '';

            $query_rsStrategicPlanProgram =  $db->prepare("SELECT * FROM tbl_strategic_plan_programs WHERE progid =:progid AND strategic_plan_id=:strategic_plan_id");
            $query_rsStrategicPlanProgram->execute(array(":progid" => $progid, ":strategic_plan_id" => $strategic_plan_id));
            $row_rsStrategicPlanProgram = $query_rsStrategicPlanProgram->fetch();
            $totalRows_rsStrategicPlanProgram = $query_rsStrategicPlanProgram->rowCount();

            if ($totalRows_rsStrategicPlanProgram > 0) {
                $strategic_plan_program_id = $row_rsStrategicPlanProgram['id'];
                $strategic_plan_id = $row_rsStrategicPlanProgram['strategic_plan_id'];
                $strategic_objective_id = $row_rsStrategicPlanProgram['strategic_objective_id'];
                $strategy_id = $row_rsStrategicPlanProgram['strategy_id'];

                $query_rsEndYear =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE id=:strategic_plan_id ");
                $query_rsEndYear->execute(array(":strategic_plan_id" => $strategic_plan_id));
                $row_rsEndYear = $query_rsEndYear->fetch();
                $totalRows_rsEndYear = $query_rsEndYear->rowCount();

                if ($totalRows_rsEndYear) {
                    $strategic_plan_start_year = $row_rsEndYear['starting_year'];
                    $strategic_plan = $row_rsEndYear['plan'];
                    $duration = $row_rsEndYear['years'];

                    for ($j = 0; $j < $duration; $j++) {
                        $dispyear =  $strategic_plan_start_year + 1;
                        $output_table_header .= ' <th colspan="2"> ' . $strategic_plan_start_year . '/' . $dispyear . '</th>';
                        $output_table_header_rowspan .= '<th>Target</th><th>Budget</th>';
                        $strategic_plan_start_year++;
                    }

                    $query_outputIndicator = $db->prepare(" SELECT g.id,  g.indicator, g.output, i.indicator_name, i.indid, unit FROM tbl_progdetails g INNER JOIN tbl_indicator i ON i.indid = g.indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE g.progid = :progid AND strategic_plan_id=:strategic_plan_id GROUP BY g.indicator");
                    $query_outputIndicator->execute(array(":progid" => $progid, ":strategic_plan_id" => $strategic_plan_id));
                    $total_outputIndicator = $query_outputIndicator->rowCount();
                    $output_table_body = '';
                    if ($total_outputIndicator > 0) {
                        while ($row_outputIndicator = $query_outputIndicator->fetch()) {
                            $strategic_start_year = $row_rsEndYear['starting_year'];

                            $indicator = $row_outputIndicator['indicator'];
                            $output = $row_outputIndicator['indicator_name'];
                            $indicator_name = $row_outputIndicator['unit'] . " of " . $row_outputIndicator['indicator_name'];
                            $output_table_body .= '<tr> <td>' . $output  . '</td> <td>' . $indicator_name . '</td>';

                            for ($i = 0; $i < $duration; $i++) {
                                $query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid = :progid and year = :progsyear and indicator =:indicator ");
                                $query_progdetails->execute(array(':progid' => $progid, ':progsyear' => $strategic_start_year, ':indicator' => $indicator));
                                $row_progdeatils = $query_progdetails->fetch();
                                $total_progdetails = $query_progdetails->rowCount();
                                $budget = $total_progdetails > 0 ? $row_progdeatils['budget'] : 0;
                                $target = $total_progdetails > 0 ? $row_progdeatils['target'] : 0;
                                $output_table_body .= '<td>' . number_format($target, 2) . '</td><td>' . number_format($budget, 2) . '</td>';
                                $strategic_start_year++;
                            }

                            $output_table_body .= '</tr>';
                        }
                    }
                }


                $query_years = $db->prepare("SELECT * FROM `tbl_strategic_plan_objectives`  WHERE id=:strategic_objective_id");
                $query_years->execute(array(":strategic_objective_id" => $strategic_objective_id));
                $row_years = $query_years->fetch();
                $objective = ($row_years) ?  $row_years['objective'] : '';

                $query_strategy =  $db->prepare("SELECT * FROM tbl_objective_strategy where id=:strategy_id");
                $query_strategy->execute(array(":strategy_id" => $strategy_id));
                $row_strategy = $query_strategy->fetch();
                $totalRows_strategy = $query_strategy->rowCount();
                $strategy = ($totalRows_strategy > 0) ? $row_strategy['strategy']  :   '';
            }

            $input =  '
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs txt-cyan" role="tablist">
                        <li class="active">
                            <a href="#1" role="tab" data-toggle="tab"><div style="color:#673AB7">Program Details</div></a>
                        </li>
                        <li>
                            <a href="#2" role="tab" data-toggle="tab"><div style="color:#673AB7">Output Details</div></a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane active" id="1">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body" style="margin-top:5px; margin-bottom:5px">
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-action active">Program Name: ' . $program_name . ' </li>
                                    </ul>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong><font color="#9C27B0">Program Problem Statement: </font></strong>' . $program_problem_statement . '</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12"><strong><font color="#9C27B0">Strategic Plan: </font></strong>' . $strategic_plan . '</div>
                                        <div class="col-md-12"><strong><font color="#9C27B0">Strategic Objective: </font></strong>' . $objective . '</div>
                                        <div class="col-md-12"><strong><font color="#9C27B0">Strategy: </font></strong>' . $strategy . '</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><strong><font color="#9C27B0">' . $ministrylabel . ': </font></strong>' . $program_department . ' </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><strong><font color="#9C27B0">' . $departmentlabel . ': </font></strong>' . $program_section . ' </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><strong><font color="#9C27B0">' . $directoratelabel . ': </font></strong>' . $program_directorate . ' </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><strong><font color="#9C27B0">Description: </font></strong>' . $description . '</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="2">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="funding_table">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">Output</th>
                                                        <th rowspan="2">Indicator</th>
                                                        ' . $output_table_header . '
                                                    </tr>
                                                    <tr>
                                                    ' . $output_table_header_rowspan . '
                                                    </tr>
                                                </thead>
                                                <tbody id="output_table" >
                                                    ' . $output_table_body . '
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }


        echo $input;
    }
} catch (PDOException $ex) {
    var_dump($ex);
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
