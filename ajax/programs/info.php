<?php
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
    $query_item = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
    $query_item->execute(array(":progid" => $progid));
    $row_item = $query_item->fetch();
    $rows_count = $query_item->rowCount();
    if ($rows_count > 0) {
        $program_name = $row_item['progname'];
        $program_problem_statement = $row_item['problem_statement'];
        $description = $row_item['description'];
        $linked_to_strategic_plan = $row_item['strategic_obj'];
        $strategy_id = $row_item['progstrategy'];
        $program_type = $row_item['program_type'];
        $progYears = $row_item['years'];
        $progStartingYear = $row_item['syear'];
        $department_id = $row_item['projsector'];
        $sector_id = $row_item['projdept'];
        $directorate = $row_item['directorate'];
        $program_department = get_sections($department_id);
        $program_section = get_sections($sector_id);
        $program_directorate = get_sections($directorate);
        $program_start_year = $progStartingYear;
        $progendingYear = ($progStartingYear + $progYears) - 1;
        $sfinyear = $progStartingYear + 1;
        $endfinyear = $progendingYear + 1;

        $output_table_header = $output_table_header_rowspan = '';
        $dispyear  = $program_start_year;
        for ($j = 0; $j < $progYears; $j++) {
            $dispyear++;
            $output_table_header .= ' <th colspan="2"> ' . $program_start_year . '/' . $dispyear . '</th>';
            $output_table_header_rowspan .= '<th>Target</th><th>Budget</th>';
            $program_start_year++;
        }


        $query_outputIndicator = $db->prepare(" SELECT g.id,  g.indicator, g.output, i.indicator_name, i.indid, unit FROM tbl_progdetails g INNER JOIN tbl_indicator i ON i.indid = g.indicator inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE g.progid = :progid GROUP BY g.indicator");
        $query_outputIndicator->execute(array(":progid" => $progid));
        $total_outputIndicator = $query_outputIndicator->rowCount();
        $output_table_body = '';
        if ($total_outputIndicator > 0) {
            while ($row_outputIndicator = $query_outputIndicator->fetch()) {
                $progsyear = $row_item['syear'];
                $indicator = $row_outputIndicator['indicator'];
                $output = $row_outputIndicator['indicator_name'];
                $indicator_name = $row_outputIndicator['unit'] . " of " . $row_outputIndicator['indicator_name'];
                $output_table_body .= '<tr> <td>' . $output  . '</td> <td>' . $indicator_name . '</td>';

                for ($i = 0; $i < $progYears; $i++) {
                    $query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid = :progid and year = :progsyear and indicator =:indicator ");
                    $query_progdetails->execute(array(':progid' => $progid, ':progsyear' => $progsyear, ':indicator' => $indicator));
                    $row_progdeatils = $query_progdetails->fetch();
                    $total_progdetails = $query_progdetails->rowCount();
                    $budget = $total_progdetails > 0 ? $row_progdeatils['budget'] : 0;
                    $target = $total_progdetails > 0 ? $row_progdeatils['target'] : 0;
                    $output_table_body .= '<td>' . number_format($target, 2) . '</td><td>' . number_format($budget, 2) . '</td>';
                    $progsyear++;
                }
                $output_table_body .= '</tr>';
            }
        }

        $strategic_plan_div = '';
        if ($program_type == 1 || $linked_to_strategic_plan != "") {
            $query_years = $db->prepare("SELECT * FROM `tbl_strategic_plan_objectives` o INNER JOIN tbl_key_results_area k ON k.id = o.kraid INNER JOIN tbl_strategicplan p ON p.id = k.spid WHERE o.id ='$linked_to_strategic_plan' LIMIT 1");
            $query_years->execute();
            $row_years = $query_years->fetch();
            if ($row_years) {
                $years = $row_years['years'];
                $startyear = $row_years['starting_year'];
                $endyear = ($startyear + $years) - 1;
                $objective = $row_years['objective'];
                $strategicPlan = $row_years['plan'];

                $query_strategy =  $db->prepare("SELECT * FROM tbl_objective_strategy where id=:strategy_id");
                $query_strategy->execute(array(":strategy_id" => $strategy_id));
                $row_strategy = $query_strategy->fetch();
                $totalRows_strategy = $query_strategy->rowCount();
                $strategy_options = '<option value="">.... Select Strategy from list ....</option>';
                $strategy = ($totalRows_strategy > 0) ? $row_strategy['strategy']  :   '';

                $spsfinyear = $startyear + 1;
                $spefinyear = $endyear + 1;
                $strategic_plan_div = '
                <div class="row"> 
                    <div class="col-md-12"><strong><font color="#9C27B0">Strategic Plan: </font></strong>' . $strategicPlan . '</div>
                    <div class="col-md-12"><strong><font color="#9C27B0">Strategic Objective: </font></strong>' . $objective . '</div>
                    <div class="col-md-12"><strong><font color="#9C27B0">Strategy: </font></strong>' . $strategy . '</div>
                </div>';
            }
        }


        $quarterly_budgets_table_body = $quarterly_targets_head = $quarterly_targets_table_body = "";
        if ($program_type == 1) {

        } else {
            $thead_1 = $thead_2 = '';
            $table_body = "";
            $program_start_year  = $progStartingYear;
            for ($j = 0; $j < $progYears; $j++) {
                $program_end_year  = $program_start_year + 1;
                $thead_1  .= '<th colspan="4" class="text-center">' . $program_start_year . '/' . $program_end_year . '</th>';
                $thead_2 .= '<th colspan="4" class="text-center">Target</th>';
                $program_start_year++;
            }

            $query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.progid =:progid GROUP BY d.indicator");
            $query_outputdetails->execute(array(":progid" => $progid));
            $rowCounts = $query_outputdetails->rowCount();
            if ($rowCounts > 0) {
                $sn = 0;
                while ($row_outputdetails = $query_outputdetails->fetch()) {
                    $sn++;
                    $outputid = $row_outputdetails["id"];
                    $output = $row_outputdetails["indicator_name"];
                    $indicatorid = $row_outputdetails["indicator"];
                    $unitid = $row_outputdetails["indicator_unit"];
                    $target = $row_outputdetails["target"];

                    $query_indunit =  $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unitid'");
                    $query_indunit->execute();
                    $rows_indunit = $query_indunit->fetch();
                    $unit = $rows_indunit['unit'];
                    $program_start_year = $progStartingYear;

                    $quarterly_targets_table_body  .= '<tr><td>' . $output . ' </td>';
                    for ($j = 0; $j < $progYears; $j++) {
                        $query_targetdetails = $db->prepare("SELECT * FROM tbl_independent_programs_quarterly_targets WHERE progid =:progid AND year=:year AND opid=:outputid");
                        $query_targetdetails->execute(array(":progid" => $progid, ":year" => $program_start_year, ":outputid" => $outputid));
                        $row_targetdetails = $query_targetdetails->fetch();
                        $pbbid = $row_targetdetails ? $row_targetdetails['id'] : 0;
                        $targetQ1 = $row_targetdetails ? $row_targetdetails['Q1'] : 0;
                        $targetQ2 = $row_targetdetails ? $row_targetdetails['Q2'] : 0;
                        $targetQ3 = $row_targetdetails ? $row_targetdetails['Q3'] : 0;
                        $targetQ4 = $row_targetdetails ? $row_targetdetails['Q4'] : 0;

                        $quarterly_targets_table_body  .= ' 
                        <td>' . number_format($targetQ1, 2) . '</td>
                        <td>' . number_format($targetQ2, 2) . '</td>
                        <td>' . number_format($targetQ3, 2) . '</td>
                        <td>' . number_format($targetQ4, 2) . '</td>';
                        $program_start_year++;
                    }
                    $quarterly_targets_table_body  .= '
                    </tr>';
                }
            }

            $quarterly_targets_head .= '
           <tr> 
                <th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                ' . $thead_1 . ' 
            </tr>
            <tr> 
            ' . $thead_2 . '
            </tr>';
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
                    <li>
                        <a href="#3" data-toggle="tab"><div style="color:#673AB7">Program Quartely Targets</div></a>
                    </li>
                    <li>
                        <a href="#4" data-toggle="tab"><div style="color:#673AB7">Program Budget</div></a>
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
                                ' . $strategic_plan_div . '
                                <div class="row"> 
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><strong><font color="#9C27B0">Start Year: </font></strong>' . $progStartingYear . '/' . $sfinyear . '</div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><strong><font color="#9C27B0">End Year: </font></strong>' . $progendingYear . '/' . $endfinyear . '</div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><strong><font color="#9C27B0">Duration: </font></strong>' . $progYears . ' Year(s)</div>
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
                <div class="tab-pane" id="3">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="funding_table">
                                            <thead>
                                               ' . $quarterly_targets_head . '
                                            </thead>
                                            <tbody id="output_table" >
                                                ' . $quarterly_targets_table_body . '
                                            </tbody>
                                        </table> 
                                    </div>																				
                                </div> 
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="4">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="funding_table">
                                        <thead>
                                            <tr>
                                                <th>Year</th>
                                                <th>Q1</th>  
                                                <th>Q2</th>  
                                                <th>Q3</th>  
                                                <th>Q4</th>  
                                            </tr>
                                        </thead>
                                        <tbody id="output_table" >
                                            ' . $quarterly_budgets_table_body . '
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
