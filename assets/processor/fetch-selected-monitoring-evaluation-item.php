<?php

include_once "controller.php";

$projid = $_POST['itemId'];
$ME1 = '';
$ME2 = '';
$ME3 = '';


    $query_rsOCProj =  $db->prepare("SELECT * FROM tbl_projects  WHERE projid ='$projid'");
    $query_rsOCProj->execute();
    $row_rsOCProj = $query_rsOCProj->fetch();
    $totalRows_rsOCProj = $query_rsOCProj->rowCount();
    $projoutcome =  $row_rsOCProj['outcome'];
    $projoutcomeindicator =  $row_rsOCProj['outcome_indicator'];
 

    $query_rsOutcome =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid ='$projid'");
    $query_rsOutcome->execute();
    $row_rsOutcome = $query_rsOutcome->fetch();
    $totalRows_rsOutcome = $query_rsOutcome->rowCount();
    $expected_ocid = $row_rsOutcome['id'];

    $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator`   WHERE indid=:indid ");
    $query_indicator->execute(array(":indid" => $projoutcomeindicator));
    $row_indicator = $query_indicator->fetch();
    $unitid = $row_indicator['indicator_unit'];
    $ocindicator = $row_indicator['indicator_name'];
    $calcid = $row_indicator['indicator_calculation_method'];

  
    $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
    $query_Indicator->execute(array(":unit" => $unitid));
    $row = $query_Indicator->fetch();
    $ocunit = $row['unit'];

    $query_Indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
    $query_Indicator_cal->execute(array(':calcid'=>$calcid));
    $row_cal = $query_Indicator_cal->fetch();
    $Outcomecalc_method = $row_cal['method'];

    $OutComeSource =   $row_rsOutcome['data_source']; 
    $Outcomeevaluationfreq = $row_rsOutcome['evaluation_frequency'];
   
    $outcomereporting_timeline =  explode(",", $row_rsOutcome['reporting_timeline']);
    $OutComeTimeline = [];
    for ($i  = 0; $i < count($outcomereporting_timeline); $i++) {
        $query_rsOutcomeTimeline =  $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE fqid ='$outcomereporting_timeline[$i]'");
        $query_rsOutcomeTimeline->execute();
        $row_rsOutcomeTimeline = $query_rsOutcomeTimeline->fetch();
        $totalRows_rsOutcomeTimeline  = $query_rsOutcomeTimeline->rowCount();

        $OutComeTimeline[] = $totalRows_rsOutcomeTimeline ?  $row_rsOutcomeTimeline['frequency']: '';
        $counter = $i + 1;
    }

    $query_rsTotalOCbase =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid ='$projid'");
    $query_rsTotalOCbase->execute();
    $row_rsTotalOCbase = $query_rsTotalOCbase->fetch();

 
    $ME2 = '
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header"> 
                    <li class="list-group-item list-group-item list-group-item-action active">Outcome: ' . $projoutcome . ' </li>
                </div> 
                <div class="body">   
                    <div class="row clearfix">  
                        <div class="col-md-12"> 
                            <ul class="list-group"> 
                                <li class="list-group-item"><strong>Indicator: </strong>' . $ocindicator . ' </li>  
                            </ul>
                        </div>  
                        <div class="col-md-6"> 
                            <ul class="list-group"> 
                                <li class="list-group-item"><strong>Unit of Measure: </strong> ' . $ocunit . '</li> 
                                <li class="list-group-item"><strong>Data Source: </strong>' . $OutComeSource. ' </li>
                            </ul>
                        </div>
                        <div class="col-md-6"> 
                            <ul class="list-group">  
                                <li class="list-group-item"><strong>Data Collection Time: </strong>' . $Outcomeevaluationfreq . ' Years</li>
                            </ul>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>'; 
 
    $query_rsProjRisk2 =  $db->prepare("SELECT * FROM tbl_projectrisks r INNER JOIN tbl_projrisk_categories c ON c.rskid = r.rskid WHERE projid ='$projid' AND r.type ='2'");
    $query_rsProjRisk2->execute();
    $row_rsProjRisk2 = $query_rsProjRisk2->fetch();
    $totalRows_rsProjRisk2 = $query_rsProjRisk2->rowCount();
    if($totalRows_rsProjRisk2 > 0){
        $ME2 .= '
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h5 class="">
                            <strong> Outcome Risks and Assumptions </strong> 
                        </h5>        
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="50%">Outcome Risks</th> 
                                        <th width="45%"> Assumptions</th> 
                                    </tr>
                                </thead>
                                <tbody id="funding_table_body" >';
                                    $RSKcounter = 0;
                                    do {
                                        $rskcatogory = $row_rsProjRisk2['category'];
                                        $assumption = $row_rsProjRisk2['assumption'];
                                        $RSKcounter++;
                                        $ME2 .= ' 
                                        <tr>  
                                            <td width="5%">' . $RSKcounter  . ' </td>
                                            <td width="50%">' . $rskcatogory  . '</td> 
                                            <td width="45%">' . $assumption  . '</td> 
                                        </tr>';
                                    } while ($row_rsProjRisk2 = $query_rsProjRisk2->fetch());
                                    $ME2 .= '  
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

    $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid ='$projid' ORDER BY id ASC");
    $query_rsOutput->execute();
    $row_rsOutput = $query_rsOutput->fetch();
    $totalRows_rsOutput = $query_rsOutput->rowCount();
    
    if($totalRows_rsOutput>0){
        $ME3 = '  
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">  
                    <div class="body">';
                        if ($totalRows_rsOutput > 0) {
                            $opcounter = 0;
                            do {
                                $opcounter++;
                                $opid = $row_rsOutput['id'];
                                $oipid = $row_rsOutput['outputid'];
                                $indicatorID = $row_rsOutput['indicator'];

                                $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` WHERE indid=:indid ");
                                $query_indicator->execute(array(":indid" => $indicatorID));
                                $row_indicator = $query_indicator->fetch();
                                $unitid = $row_indicator['indicator_unit'];
                                $indname = $row_indicator['indicator_name'];
                                $calcid = $row_indicator['indicator_calculation_method'];

                                $query_Indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
                                $query_Indicator_cal->execute(array(':calcid'=>$calcid));
                                $row_cal = $query_Indicator_cal->fetch();
                                $count_cal = $query_Indicator_cal->rowCount();
                                $Outputcalc_method = $count_cal > 0 ? $row_cal['method']: '';

                                $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
                                $query_Indicator->execute(array(":unit" => $unitid));
                                $row = $query_Indicator->fetch();
                                $opunit = $row['unit'];

                                $query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$oipid'");
                                $query_out->execute();
                                $row_out = $query_out->fetch();
                                $outputName = $row_out['output'];

                                $query_rsOutputDetails =  $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE outputid ='$opid'");
                                $query_rsOutputDetails->execute();
                                $row_rsOutputDetails = $query_rsOutputDetails->fetch();
                                $totalRows_rsOutputDetails = $query_rsOutputDetails->rowCount();
                                
                                $outputmonitoring_frequency = $row_rsOutputDetails['monitoring_frequency'];
                                $query_rsOutputevaluationFreq =  $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE fqid ='$outputmonitoring_frequency'");
                                $query_rsOutputevaluationFreq->execute();
                                $row_rsOutputevaluationFreq = $query_rsOutputevaluationFreq->fetch();
                                $totalRows_rsOutputevaluationFreq = $query_rsOutputevaluationFreq->rowCount();
                                $Outputevaluationfreq = $row_rsOutputevaluationFreq['frequency'];

                                
                                $outputreporting_timeline =  explode(",", $row_rsOutputDetails['reporting_timeline']);
                                $Output_reportingTimeline = [];
                                for ($i  = 0; $i < count($outputreporting_timeline); $i++) {
                                    $query_rsOutput_reportingTimeline =  $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE fqid ='$outputreporting_timeline[$i]'");
                                    $query_rsOutput_reportingTimeline->execute();
                                    $row_rsOutput_reportingTimeline = $query_rsOutput_reportingTimeline->fetch();
                                    $totalRows_rsOutput_reportingTimeline  = $query_rsOutput_reportingTimeline->rowCount();
                                    $Output_reportingTimeline[] = $row_rsOutput_reportingTimeline['frequency'];
                                    $counter = $i + 1;
                                }

                                $query_rsProjRisk4 =  $db->prepare("SELECT * FROM tbl_projectrisks r INNER JOIN tbl_projrisk_categories c ON c.rskid = r.rskid WHERE r.projid ='$projid' AND r.type ='3' AND outputid ='$opid'");
                                $query_rsProjRisk4->execute();
                                $row_rsProjRisk4 = $query_rsProjRisk4->fetch();
                                $totalRows_rsProjRisk4 = $query_rsProjRisk4->rowCount();

                                $ME3 .= '
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="header"> 
                                                <li class="list-group-item list-group-item list-group-item-action active">Output ' . $opcounter . ': ' . $outputName . ' </li>
                                            </div> 
                                            <div class="body">   
                                                <div class="row clearfix">
                                                    <div class="col-md-12"> 
                                                        <ul class="list-group"> 
                                                            <li class="list-group-item"><strong>Indicator: </strong>' . $indname . ' </li>   
                                                        </ul>
                                                    </div> 
                                                    <div class="col-md-6"> 
                                                        <ul class="list-group"> 
                                                            <li class="list-group-item"><strong>Unit of Measure: </strong>' . $opunit . '</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <ul class="list-group">  
                                                            <li class="list-group-item"><strong>Monitoring Frequency: </strong>' . $Outputevaluationfreq . ' </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <ul class="list-group"> 
                                                            <li class="list-group-item"><strong>Reporting Timeline: </strong>' . implode(',',$Output_reportingTimeline) . '</li>
                                                        </ul>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                                $query_rsdissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid='$opid' AND locations IS NOT NULL");
                                $result = $query_rsdissegragations->execute();
                                $row_rsdissegragations = $query_rsdissegragations->fetch();
                                $totalRows_rsdissegragations = $query_rsdissegragations->rowCount();
                                $data = '';

                                if ($totalRows_rsdissegragations > 0) {
                                    $st = 0;
                                    do {
                                        $row = 0;
                                        $st++;
                                        $opstate = $row_rsdissegragations['outputstate'];
                                        $oprow = $opstate . $st;
                                        $locations = explode(",", $row_rsdissegragations['locations']);
                                        $query_rsForest = $db->prepare("SELECT id, state FROM tbl_state WHERE  id='$opstate' LIMIT 1");
                                        $query_rsForest->execute();
                                        $row_rsForest = $query_rsForest->fetch();
                                        $forest = $row_rsForest['state'];

                                        $data .= '
                                        <tr>
                                            <td>' . $st . '</td>
                                            <td colspan="4"> <strong>
                                                ' . $forest . ' ' . $level3label . ' </strong> 
                                            </td> 
                                        </tr>';

                                        $query_rsproject_dissegragations = $db->prepare("SELECT * FROM tbl_project_results_level_disaggregation WHERE projoutputid	='$opid' AND projid='$projid' AND opstate='$opstate' AND type=3 ");
                                        $result = $query_rsproject_dissegragations->execute();
                                        $row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch();
                                        $totalRows_rsproject_dissegragations = $query_rsproject_dissegragations->rowCount();

                                        do {
                                            $rowno = $opstate . $st . $row;
                                            $locationName = $row_rsproject_dissegragations['name'];
                                            $responsible = $row_rsproject_dissegragations['responsible'];
 
                                                $row++;
                                                $query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
                                                $query_rsTeam->execute(array(":ptid" => $responsible));
                                                $row_rsTeam = $query_rsTeam->fetch();
                                                $totalRows_rsTeam = $query_rsTeam->rowCount();
                                                $resp = $row_rsTeam['fullname'];

                                                $data .= '
                                                <tr id=""> 
                                                    <td>' . $st . "." . $row . '</td> 
                                                    <td>
                                                    ' . $locationName . '
                                                    </td>
                                                    <td>
                                                    ' . $resp . '
                                                    </td>  
                                                </tr>'; 
                                        } while ($row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch());
                                    } while ($row_rsdissegragations = $query_rsdissegragations->fetch()); 
                                }else{
                                    $query_rsproject_dissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid	='$opid' AND projid='$projid' ");
                                    $result = $query_rsproject_dissegragations->execute();
                                    $row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch();
                                    $totalRows_rsproject_dissegragations = $query_rsproject_dissegragations->rowCount();
                                    $row =0;

                                    if($totalRows_rsproject_dissegragations>0){
                                        do {
                                            $forest = $row_rsproject_dissegragations['outputstate'];
                                            $responsible = explode(',', $row_rsproject_dissegragations['responsible']); 
                                            $row++;
                                            $resp = array();
                                            for($r = 0; $r< count($responsible); $r++){
                                                $ptid = $responsible[$r];
                                                $query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
                                                $query_rsTeam->execute(array(":ptid" => $ptid));
                                                $row_rsTeam = $query_rsTeam->fetch();
                                                $totalRows_rsTeam = $query_rsTeam->rowCount();
    
                                                $resp[] =  $totalRows_rsTeam > 0 ? $row_rsTeam['fullname']: '';
                                            }

                                            $query_rsForest = $db->prepare("SELECT id, state FROM tbl_state WHERE  id='$forest' LIMIT 1");
                                            $query_rsForest->execute();
                                            $row_rsForest = $query_rsForest->fetch();
                                            $locationName = $row_rsForest['state'];

                                            $data .= '
                                            <tr id=""> 
                                                <td>' .  $row . '</td> 
                                                <td>
                                                ' . $locationName . '
                                                </td>
                                                <td>
                                                ' . implode(',', $resp) . '
                                                </td>  
                                            </tr>';
                                            
                                        } while ($row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch());
                                    }
                                }

                                    $ME3 .= '
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card"> 
                                                <div class="header">
                                                    <h5 class=""><strong> Output Locations Responsible</strong> </h5>        
                                                </div>
                                                <div class="body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%">#</th>
                                                                    <th width="50%">Output Locations</th> 
                                                                    <th width="45%">Responsible</th> 
                                                                </tr>
                                                            </thead>
                                                            <tbody id="funding_table_body" >';
                                                                $ME3 .= $data;
                                                                $ME3 .= '  
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

                                    $ME3 .= '
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card"> 
                                                <div class="header">
                                                    <h5 class=""> <strong>Output Risks and Assumptions</strong> </h5>        
                                                </div>
                                                <div class="body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%">#</th>
                                                                    <th width="50%">Output Risks</th> 
                                                                    <th width="45%">Assumptions</th> 
                                                                </tr>
                                                            </thead>
                                                            <tbody id="funding_table_body" >';
                                                                $RSKcounter = 0;
                                                                do {
                                                                    $rskcatogory = $row_rsProjRisk4['category'];
                                                                    $assumption = $row_rsProjRisk4['assumption'];
                                                                    $RSKcounter++;
                                                                    $ME3 .= ' 
                                                                    <tr>  
                                                                        <td width="5%">' . $RSKcounter  . ' </td>
                                                                        <td width="50%">' . $rskcatogory  . '</td>
                                                                        <td width="45%">' . $assumption  . '</td>
                                                                    </tr>';
                                                                } while ($row_rsProjRisk4 = $query_rsProjRisk4->fetch());
                                                                $ME3 .= '  
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                            } while ($row_rsOutput = $query_rsOutput->fetch());
                        } 
                        $ME3 .= '
                    </div>
                </div>
            </div>
        </div>';
    }

    $input =  '
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs txt-cyan" role="tablist">
                <li class="active">
                    <a href="#2" role="tab" data-toggle="tab"><div style="color:#673AB7">Outcome Details</div></a>
                </li>
                <li>
                    <a href="#3" role="tab" data-toggle="tab"><div style="color:#673AB7">Output Details</div></a>
                </li> 
            </ul>
        </div>
        <div class="card-body tab-content">
            <div class="tab-pane active" id="2"> 
                ' .  $ME2 . '
            </div>
            <div class="tab-pane" id="3"> 
                ' . $ME3 . '
            </div>   
        </div>
    </div>';

echo $input;
