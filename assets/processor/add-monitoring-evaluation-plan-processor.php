<?php
include_once "controller.php";
if (isset($_POST['get_risks'])) {
    $risk_type = $_POST['get_risks'];
    $query_rsRisk =  $db->prepare("SELECT * FROM tbl_projrisk_categories");
    $query_rsRisk->execute();
    $row_rsRisk = $query_rsRisk->fetch();
    $totalRows_rsRisk = $query_rsRisk->rowCount();
    $input = '<option value="">... Select from list ...</option>';

    if ($totalRows_rsRisk > 0) {
        do {
            $type = explode(',',$row_rsRisk['type']); 
            if(in_array(3, $type)) {
                $input .= '<option value="' . $row_rsRisk['rskid'] . '">' . $row_rsRisk['category'] . ' </option>';
            }
        } while ($row_rsRisk = $query_rsRisk->fetch());
    } else {
        $input .= '<option value="">No Risks Found</option>';
    }
    echo $input;
}

if (isset($_POST['impact_indicator'])) {
    $indid = $_POST['impact_indicator'];

    $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator`   WHERE indid=:indid ");
    $query_indicator->execute(array(":indid" => $indid));
    $row_indicator = $query_indicator->fetch();
    $unitid = $row_indicator['indicator_unit'];
    $calcid = $row_indicator['indicator_calculation_method'];
 
    $query_Indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
    $query_Indicator_cal->execute(array(':calcid'=>$calcid));
    $row_cal = $query_Indicator_cal->fetch();
    $calc_method = $row_cal['method'];

 
    $query_rsIndType = $db->prepare("SELECT * FROM tbl_indicator_beneficiaries WHERE indicatorid= '$indid'");
    $query_rsIndType->execute();
    $row_rsIndType = $query_rsIndType->fetch();
    $indIndTypecount = $query_rsIndType->rowCount();

    $type = $dissagragated = $dissagragated_category = $benName = $Names = '';
    $inddissagragated_category = $indbenName = $indNames = '';
    $inddissagragated = 2;

    if ($indIndTypecount > 0) {
        do {
            $type = $row_rsIndType['type'];
            if ($type == 1) {
                $dissagragated = $row_rsIndType['dissagragated'];
                $dissagragated_category = $row_rsIndType['dissagragated_category'];
                $benName = $row_rsIndType['beneficiary'];
                $ben_id = $row_rsIndType['id'];

                $query_rsIndDiss = $db->prepare("SELECT * FROM tbl_indicator_beneficiary_dissegragation WHERE ben_id= '$ben_id'");
                $query_rsIndDiss->execute();
                $row_rsIndDiss = $query_rsIndDiss->fetch();
                $indIndTypecountdiss = $query_rsIndDiss->rowCount();
                $Names = $row_rsIndDiss['name'];
            } else if ($type == 2) {
                $inddissagragated = $row_rsIndType['dissagragated'];
                $inddissagragated_category = $row_rsIndType['dissagragated_category'];
                $indbenName = $row_rsIndType['beneficiary'];
                $indben_id = $row_rsIndType['id'];
                if ($inddissagragated == 1) {
                    $query_rsIndDiss = $db->prepare("SELECT * FROM tbl_indicator_dissegragations WHERE ben_id= '$indben_id'");
                    $query_rsIndDiss->execute();
                    $row_rsIndDiss = $query_rsIndDiss->fetch();
                    $indIndTypecountdiss = $query_rsIndDiss->rowCount();
                    $indNames = $row_rsIndDiss['name'];
                }
            }
        } while ($row_rsIndType = $query_rsIndType->fetch());
    }

    $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
    $query_Indicator->execute(array(":unit" => $unitid));
    $row = $query_Indicator->fetch();
    $unitofmeasure = $row['unit'];
    $ME1 = '';
    echo json_encode(array("impact_calc_method" => $calc_method, "unitofmeasure" => $unitofmeasure));
}

if (isset($_POST['addoutput'])) {
    if (isset($_POST['opid'])) {
        $output = $_POST['opid'];
        $projid = $_POST['projid'];
        $outputIndicator  = $_POST['output_indicator'];
        $outputMonitorigFreq = $_POST['outputMonitorigFreq'];
        //$outputReportingTimeline = implode(",", $_POST['outputReportingTimeline']);
        $datecreated = date("Y-m-d");
        $createdby = $_POST['user_name'];
        $createdby = 1;
		$stage = 10;
		
		$query_proj = $db->prepare("SELECT projevaluation FROM tbl_projects WHERE projid =:projid ");
		$query_proj->execute(array(":projid" => $projid));
		$row_proj = $query_proj->fetch();
		$projevaluation = $row_proj['projevaluation'];
		
		if($projevaluation == 0){
			$query_update = $db->prepare("UPDATE tbl_projects SET projstage = :stage WHERE projid = :projid");
			$query_update->execute(array(":stage" => $stage, ":projid" => $projid));
		}

        $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_outputs_mne_details`(projid, outputid, indicator, monitoring_frequency, date_created, created_by) VALUES(:projid, :output, :indicator,  :monitoring_frequency, :date_added,:added_by)");
        $result1  = $insertSQL1->execute(array(":projid" => $projid, ":output" => $output,":indicator" => $outputIndicator,  ":monitoring_frequency" => $outputMonitorigFreq, ":date_added" => $datecreated, ":added_by" => $createdby));

        if (isset($_POST['outputrisk'])) {
            for ($i = 0; $i < count($_POST['outputrisk']); $i++) {
                $riskid = $_POST['outputrisk'][$i];
                $assumption = $_POST['output_assumptions'][$i];
                $type = 3;
                $insertSQL1 = $db->prepare("INSERT INTO `tbl_projectrisks`(projid, rskid, outputid, type, assumption) VALUES(:projid, :rskid, :outputid, :type, :assumption )");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":rskid" => $riskid, ":outputid" => $output, ":type" => $type, ":assumption" => $assumption));
            }
        }

        if (isset($_POST['forest'])) {
            for ($i = 0; $i < count($_POST['forest']); $i++) {
                $opstate = $_POST['forest'][$i];
                $team = $_POST['team' . $opstate];
                $diss_val = $_POST['diss_id' . $opstate];
                $var_count = count($diss_val);

                for ($jp = 0; $jp < $var_count; $jp++) {
                    $diss_id = $diss_val[$jp];
                    $member = $team[$jp];
                    $insertSQL1 = $db->prepare("UPDATE tbl_output_disaggregation SET responsible = :responsible WHERE  projid=:projid  AND outputid=:outputid AND  id=:id ");
                    $result1  = $insertSQL1->execute(array(":responsible" => $member, ":projid" => $projid, ":outputid" => $output, ":id" => $diss_id));
                }
            }
        }

        if (isset($_POST['ind_forest'])) { 
            $diss_val = $_POST['diss_id'];
            for ($jp = 0; $jp < count($diss_val); $jp++) {
                $diss_id = $diss_val[$jp];
                $team = $_POST['team' . $diss_id];
                $member = implode(',', $team);
                $insertSQL1 = $db->prepare("UPDATE tbl_output_disaggregation SET responsible = :responsible  WHERE  projid=:projid  AND outputid=:outputid AND  id=:id ");
                $result1  = $insertSQL1->execute(array(":responsible" => $member, ":projid" => $projid, ":outputid" => $output , ":id" => $diss_id));
            }
        }
        if ($result1) {
            echo json_encode(array("msg" => true));
        }
    }
}

if (isset($_POST['get_output_details'])) {
    $projid = $_POST['projid'];
    $outputid = $_POST['opid'];
    $query_rsDetails =  $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid=:projid AND outputid=:outputid");
    $query_rsDetails->execute(array(":projid" => $projid, ":outputid" => $outputid));
    $row_rsDetails = $query_rsDetails->fetch();
    $totalRows_rsDetails = $query_rsDetails->rowCount();

    if ($totalRows_rsDetails > 0) { 
        $monitoringfreq = $row_rsDetails['monitoring_frequency']; 
        $reporting_timeline = explode(",",$row_rsDetails['reporting_timeline']); 
        $opid = $row_rsDetails['outputid'];

        $query_rsOPRisk =  $db->prepare("SELECT * FROM tbl_projectrisks WHERE projid=:projid AND outputid=:outputid");
        $query_rsOPRisk->execute(array(":projid" => $projid, ":outputid" => $outputid));
        $row_rsOPRisk = $query_rsOPRisk->fetch();
        $totalRows_rsOPRisk = $query_rsOPRisk->rowCount();
 

        if ($totalRows_rsOPRisk > 0) {
            $rowno = 0;
            $risks = '';
            do {
                $type = 3;
                $riskid = $row_rsOPRisk['rskid'];
                $assumption = $row_rsOPRisk['assumption'];
                $rowno++;
                $query_rsRisk =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories where type=:type");
                $query_rsRisk->execute(array(":type" => $type));
                $row_rsRisk = $query_rsRisk->fetch();
                $totalRows_rsRisk = $query_rsRisk->rowCount();

                if ($totalRows_rsRisk > 0) {
                    $input = '<option value="">... Select from list ...</option>';
                    do {
                        if ($riskid == $row_rsRisk['rskid']) {
                            $input .= '<option value="' . $row_rsRisk['rskid'] . '" selected>' . $row_rsRisk['category'] . ' </option>';
                        } else {
                            $input .= '<option value="' . $row_rsRisk['rskid'] . '">' . $row_rsRisk['category'] . ' </option>';
                        }
                    } while ($row_rsRisk = $query_rsRisk->fetch());
                } else {
                    $input .= '<option value="">No Risks Found</option>';
                }

                $risks .= '<tr id="outputrow' . $rowno . '">
                <td>' . $rowno . '</td>
                <td>
                    <select  data-id="' . $rowno . '" name="outputrisk[]" id="outputriskrow' . $rowno . '" class="form-control  selected_output" required="required">
                    ' . $input . '
                    </select>
                </td>
                <td>
                <input type="text" name="output_assumptions[]"  id="output_assumptionsrow' . $rowno . '" value="' . $assumption . '"  placeholder="Enter"  class="form-control"  required/>
                </td>
                <td>';
                if ($rowno == 1) {
                } else {
                    $risks .= '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("outputrow' . $rowno . '")>
                        <span class="glyphicon glyphicon-minus"></span> 
                        </button>';
                }


                $risks .= '</td>
                </tr>';
            } while ($row_rsOPRisk = $query_rsOPRisk->fetch());
        }
 
        $query_rsdissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid='$outputid' AND locations IS NOT NULL");
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
                        <input type="hidden" name="forest[]" id="forest' . $st . '"   value="' . $opstate . '"  />
                    </td> 
                </tr>';

                $query_rsproject_dissegragations = $db->prepare("SELECT * FROM tbl_project_results_level_disaggregation  WHERE projoutputid	='$opid' AND projid='$projid' AND opstate='$opstate' ");
                $result = $query_rsproject_dissegragations->execute();
                $row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch();
                $totalRows_rsproject_dissegragations = $query_rsproject_dissegragations->rowCount();

                do {
                    $rowno = $opstate . $st . $row;
                    $team = '<option value="" >...Select from list...</option>';
                    $locationName = $row_rsproject_dissegragations['name'];
                    $id = $row_rsproject_dissegragations['id'];
                    $responsible = $row_rsproject_dissegragations['responsible'];

                    $row++;
                    $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
                    $query_rsMembers->execute(array(":projid" => $projid));
                    $row_rsMembers = $query_rsMembers->fetch();
                    $totalRows_rsMembers = $query_rsMembers->rowCount();

                    do {
                        $ptid = $row_rsMembers['ptid'];
                        $query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
                        $query_rsTeam->execute(array(":ptid" => $ptid));
                        $row_rsTeam = $query_rsTeam->fetch();
                        $totalRows_rsTeam = $query_rsTeam->rowCount();
                        if ($responsible == $row_rsTeam['ptid']) {
                            $team .= '<option value="' . $row_rsTeam['ptid'] . '" selected>' . $row_rsTeam['fullname'] . '</option>';
                        } else {
                            $team .= '<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
                        }
                    } while ($row_rsMembers = $query_rsMembers->fetch());

                    $data .= '
                        <tr id=""> 
                            <td>' . $st . "." . $row . '</td> 
                            <td>
                            ' . $locationName . '
                                <input type="hidden" name="diss_id' . $opstate . '[]"  id="diss_id' . $rowno . '" class="form-control" value="' . $row_rsproject_dissegragations['id'] . '"  required />
                            </td>
                            <td>
                            <select name="team' . $opstate . '[]"  id="team' . $rowno . '" class="form-control" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                    ' . $team . '
                                </select>
                            </td>  
                        </tr>';
                } while ($row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch());
            } while ($row_rsdissegragations = $query_rsdissegragations->fetch());
        }else{
            $query_opresponsible = $db->prepare("SELECT id, responsible, outputstate FROM tbl_output_disaggregation WHERE projid = '$projid' and outputid = '$opid' ");
            $query_opresponsible->execute();
            $row_opresponsible = $query_opresponsible->fetch();
            $rowCount_opresponsible = $query_opresponsible->rowCount();
             
            $rowno = 0;
            if($rowCount_opresponsible >0){
                do  {
                    $team = '<option value="" >...Select from list...</option>';
                    $responsible = explode(',',$row_opresponsible['responsible']);
                    $diss_id = $row_opresponsible['id'];
                    $projstate = $row_opresponsible['outputstate'];
 
                    $query_rsForest = $db->prepare("SELECT id, state FROM tbl_state WHERE  id='$projstate' LIMIT 1");
                    $query_rsForest->execute();
                    $row_rsForest = $query_rsForest->fetch();
                    $locationName = $row_rsForest['state'];
                    
                    $rowno++;
                    $query_rsMembers = $db->prepare("SELECT * FROM tbl_projteam2 ");
                    $query_rsMembers->execute(array(":projid" => $projid));
                    $row_rsMembers = $query_rsMembers->fetch();
                    $totalRows_rsMembers = $query_rsMembers->rowCount();

                    do {
                        $ptid = $row_rsMembers['ptid'];
                        if (in_array($row_rsMembers['ptid'], $responsible)) {
                            $team .= '<option value="' . $row_rsMembers['ptid'] . '" selected>' . $row_rsMembers['fullname'] . '</option>';
                        } else {
                            $team .= '<option value="' . $row_rsMembers['ptid'] . '">' . $row_rsMembers['fullname'] . '</option>';
                        }
                    } while ($row_rsMembers = $query_rsMembers->fetch());

                    $data .= '
                        <tr id=""> 
                            <td>' .  $rowno . '</td> 
                            <td>
                                ' . $locationName . '
                                <input type="hidden" name="diss_id[]"  id="diss_id' . $rowno . '" class="form-control" value="' . $diss_id . '"  required />
                                <input type="hidden" name="ind_forest[]" id="ind_forest' . $rowno . '" class="form-control" value=""  required />
                            </td>
                            <td>
                            <select name="team'.$diss_id.'[]" data-live-search="true" id="team' . $rowno . '" class="form-control  selectpicker" multiple="multiple" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                    ' . $team . '
                                </select>
                            </td>
                        </tr>';
                } while($row_opresponsible = $query_opresponsible->fetch());
            }else{
                $data .="not found";
            }
        } 

    $query_rs_level1 =  $db->prepare("SELECT fq.level as level1 FROM tbl_datacollectionfreq as fq INNER JOIN tbl_project_details as p ON p.workplan_interval=fq.fqid WHERE p.id=:opid");
    $query_rs_level1->execute(array(":opid" => $opid));
    $row_rs_level1 = $query_rs_level1->fetch();
    $totalRows_rs_level = $query_rs_level1->rowCount();
    $level =  $row_rs_level1['level1']; 

    $query_rs_frequency1 =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where level <= :level");
    $query_rs_frequency1->execute(array(":level" => $level));
    $row_rs_frequency1 = $query_rs_frequency1->fetch();
    $totalRows_rs_frequency1 = $query_rs_frequency1->rowCount();

    $moni = '<option value="">... Select Monitoring Frequency ...</option>';
    if ($totalRows_rs_frequency1 > 0) {
        do {
            if($monitoringfreq == $row_rs_frequency1['fqid'] ){
                $moni .= '<option value="' . $row_rs_frequency1['fqid'] . '" selected>' . $row_rs_frequency1['frequency'] . ' </option>';
            }else{
                $moni .= '<option value="' . $row_rs_frequency1['fqid'] . '">' . $row_rs_frequency1['frequency'] . ' </option>';
            }
        } while ($row_rs_frequency1 = $query_rs_frequency1->fetch());
    } else {
        $moni .= '<option value="">No Frequency Found</option>';
    } 

         
    $query_rs_level =  $db->prepare("SELECT  level FROM tbl_datacollectionfreq  WHERE fqid=:fqid");
    $query_rs_level->execute(array(":fqid" => $monitoringfreq));
    $row_rs_level = $query_rs_level->fetch();
    $totalRows_rs_level = $query_rs_level->rowCount();
    $level =  $row_rs_level['level']; 

    $query_rs_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where level >=:level");
    $query_rs_frequency->execute(array(":level" => $level));
    $row_rs_frequency = $query_rs_frequency->fetch();
    $totalRows_rs_frequency = $query_rs_frequency->rowCount();

    $input = '<option value="">... Select from list ...</option>';
    if ($totalRows_rs_frequency > 0) {
        do {
            if(in_array($row_rs_frequency['fqid'], $reporting_timeline)){
            $input .= '<option value="' . $row_rs_frequency['fqid'] . '" selected>' . $row_rs_frequency['frequency'] . ' </option>';
            }else{
                $input .= '<option value="' . $row_rs_frequency['fqid'] . '">' . $row_rs_frequency['frequency'] . ' </option>'; 
            }
        } while ($row_rs_frequency = $query_rs_frequency->fetch());
    } else {
        $input .= '<option value="">No Frequency Found</option>';
    } 

        echo json_encode(array("monitoringfreq" => $moni, "reporting_timeline" => $input, "risks" => $risks, "op_diss_state" => $data));
    }
}

if (isset($_POST['editoutput'])) {
    $opid = $_POST['opid'];
    $projid = $_POST['projid'];  
    $outputMonitorigFreq = $_POST['outputMonitorigFreq'];  
    // $outputReportingTimeline = implode(",", $_POST['outputReportingTimeline']);
    $datecreated = date("Y-m-d");
    // $createdby = $_POST['user_name'];

    $createdby = 1;
    $insertSQL1 = $db->prepare("UPDATE tbl_project_outputs_mne_details SET  monitoring_frequency=:monitoring_frequency, date_changed=:date_added, changed_by=:added_by WHERE outputid=:opid ");
    $result1  = $insertSQL1->execute(array( ":monitoring_frequency" => $outputMonitorigFreq, ":date_added" => $datecreated, ":added_by" => $createdby, ":opid" => $opid));

    if (isset($_POST['outputrisk'])) {
        $deleteQuery = $db->prepare("DELETE FROM `tbl_projectrisks` WHERE outputid=:opid");
        $results = $deleteQuery->execute(array(':opid' => $opid));

        for ($i = 0; $i < count($_POST['outputrisk']); $i++) {
            $riskid = $_POST['outputrisk'][$i];
            $assumption = $_POST['output_assumptions'][$i];
            $type = 3;
            $insertSQL1 = $db->prepare("INSERT INTO `tbl_projectrisks`(projid, outputid, rskid, type, assumption) VALUES(:projid, :outputid, :rskid, :type, :assumption )");
            $result1  = $insertSQL1->execute(array(":projid" => $projid, ":rskid" => $riskid, ":outputid" => $opid, ":type" => $type, ":assumption" => $assumption));
        }

        if (isset($_POST['forest'])) {
            var_dump("Disaggregated");
            return;
            for ($i = 0; $i < count($_POST['forest']); $i++) {
                $opstate = $_POST['forest'][$i];
                $team = $_POST['team' . $opstate];
                $diss_val = $_POST['diss_id' . $opstate];
                for ($jp = 0; $jp < count($diss_val); $jp++) {
                    $diss_id = $diss_val[$jp];
                    $member = $team[$jp];
                    $insertSQL1 = $db->prepare("UPDATE tbl_project_results_level_disaggregation SET responsible = :responsible  WHERE  projid=:projid  AND projoutputid=:outputid AND  id=:id ");
                    $result1  = $insertSQL1->execute(array(":responsible" => $member, ":projid" => $projid, ":outputid" => $opid, ":id" => $diss_id));
                }
            }
        }

        if (isset($_POST['ind_forest'])) { 
            $diss_val = $_POST['diss_id'];
            for ($jp = 0; $jp < count($diss_val); $jp++) {
                $diss_id = $diss_val[$jp];
                $team = $_POST['team' . $diss_id];
                $member = implode(',', $team);
                $insertSQL1 = $db->prepare("UPDATE tbl_output_disaggregation SET responsible = :responsible  WHERE  projid=:projid  AND outputid=:outputid AND  id=:id ");
                $result1  = $insertSQL1->execute(array(":responsible" => $member, ":projid" => $projid, ":outputid" => $opid, ":id" => $diss_id));
            }
        }
    }
    echo json_encode(array("msg" => true));
}

if (isset($_POST["deleteItem"])) {
    $itemid = $_POST['itemId'];
    $responsible = NULL;
    $report_user =NULL;
    $insertSQL1 = $db->prepare("UPDATE tbl_projects SET mne_responsible = :responsible, mne_report_users=:reportUsers WHERE  projid=:projid");
    $result1  = $insertSQL1->execute(array(":responsible" => $responsible, ":reportUsers"=>$report_user, ":projid" => $itemid));
 
    $deleteQueryR = $db->prepare("DELETE FROM `tbl_projectrisks` WHERE projid=:itemid");
    $resultsR = $deleteQueryR->execute(array(':itemid' => $itemid));

    $deleteQueryI = $db->prepare("DELETE FROM `tbl_project_expected_impact_details` WHERE projid=:itemid");
    $resultsI = $deleteQueryI->execute(array(':itemid' => $itemid));

    $deleteQueryE = $db->prepare("DELETE FROM `tbl_project_expected_outcome_details` WHERE projid=:itemid");
    $results = $deleteQueryE->execute(array(':itemid' => $itemid));

    $deleteQueryE = $db->prepare("DELETE FROM `tbl_project_outputs_mne_details` WHERE projid=:itemid");
    $results = $deleteQueryE->execute(array(':itemid' => $itemid));
  

    if ($results === TRUE) {
        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Deleted";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while deletng the record!!";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while deletng the record!!";
    }

    echo json_encode($valid);
}

if (isset($_POST['get_impact_table'])) {
    $progid = $_POST['progid'];
    $query_rsProgram = $db->prepare("SELECT * FROM tbl_programs WHERE deleted='0' and progid='$progid'");
    $query_rsProgram->execute();
    $row_rsProgram = $query_rsProgram->fetch();
    $totalRows_rsProgram = $query_rsProgram->rowCount();
    $kpi = $row_rsProgram['kpi'];
    $sectorid = $row_rsProgram['projsector'];

    $ME1 = '
    <div class="col-md-12">
        <label for="impactName" class="control-label">Impact *:</label>
        <div class="form-input">
            <input type="text" name="impactName" id="impactName" placeholder="Enter Project Impact" class="form-control">
        </div>
    </div>
    <div class="col-md-12">
        <label for="impactIndicator" class="control-label">Indicator *:</label>
        <div class="form-line">
            <select name="impactIndicator" onchange="get_impact_ind_details()" id="impactIndicator" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                <option value="">.... Select from list ....</option>';
                    $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` WHERE `indicator_category` = 'Impact' AND indicator_sector = '$sectorid' AND `active` = '1'");
                    $query_indicator->execute();
                    $row_indicator = $query_indicator->fetch();
                    do {
                        $ME1 .= ' <option value="' . $row_indicator['indid'] . '">' . $row_indicator['indicator_name'] . '</option>';
                    } while ($row_indicator = $query_indicator->fetch());

                    $ME1 .= '  
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <label for="impact_calc_method" class="control-label">Result Type *:</label>
        <div class="form-input">
            <input type="text" name="impact_calc_method" readonly id="impact_calc_method" placeholder="Enter Result Type" class="form-control" required="required">
        </div>
    </div>
    <div class="col-md-4">
        <label for="impactTarget" class="control-label">Units of Measure <span id="impunit"></span>*:</label>
        <div class="form-input">
            <input type="text" name="unitofmeasure" readonly id="unitofmeasure" placeholder="Enter Impact Target" class="form-control" required="required">
        </div>
    </div>  
    <div class="col-md-4">
        <label class="control-label">Assessment Date (Years) *:</label>
        <div class="form-line">
            <input type="hidden" name="impactEvaluation" id="impactEvaluation">
            <input type="number" name="impactEvaluationFreq" onkeyup="impact_Evaluation()" onchange="impact_Evaluation()" id="impactEvaluationFreq" class="form-control" placeholder="Enter Number of Year" required="required">
        </div>
    </div>
    <div class="col-md-12">
        <label class="control-label">Source of Data *:</label>
        <div class="form-line">
            <input type="text" name="impactdataSource"  id="impactdataSource" placeholder="Enter Impact Source of data" class="form-control" required="required">
        </div>
    </div> 
    <div class="col-md-12">
        <label class="control-label">Impact Risks and Assumptions </label>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%">Impact Risks</th>
                        <th width="60%">Assumption/s</th>
                        <th width="5%">
                            <button type="button" name="addplus" id="addplus" onclick="add_row_impact();" class="btn btn-success btn-sm">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody id="impact_table_body">
                    <tr id="row0">
                        <td> 1 </td>
                        <td>
                            <select data-id="0" name="impactrisk[]" id="impactriskrow0" class="form-control  selected_impact" required="required">';
                                $query_rsRisk =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories where type=:type");
                                $query_rsRisk->execute(array(":type" => 1));
                                $row_rsRisk = $query_rsRisk->fetch();
                                $totalRows_rsRisk = $query_rsRisk->rowCount();
                                $input = '<option value="">... Select from list ...</option>';
                                if ($totalRows_rsRisk > 0) {
                                    do {
                                        $input .= '<option value="' . $row_rsRisk['rskid'] . '">' . $row_rsRisk['category'] . ' </option>';
                                    } while ($row_rsRisk = $query_rsRisk->fetch());
                                } else {
                                    $input .= '<option value="">No Risks Found</option>';
                                }
                                $ME1 .= $input;
                                $ME1 .= '
                            </select>
                        </td>
                        <td>
                            <input type="text" name="impact_assumptions[]" id="impact_assumptionsrow0" placeholder="Enter" class="form-control"  required />
                        </td>
                        <td>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>';
    echo $ME1;
}

if (isset($_POST['get_locations'])) {
    $opid = $_POST['opid'];
    $projid = $_POST['projid'];
 
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
                    <input type="hidden" name="forest[]" id="forest' . $st . '"   value="' . $opstate . '"  />
                </td> 
            </tr>';

            $query_rsproject_dissegragations = $db->prepare("SELECT * FROM tbl_project_results_level_disaggregation WHERE projoutputid	='$opid' AND projid='$projid' AND opstate='$opstate' AND type=3 ");
            $result = $query_rsproject_dissegragations->execute();
            $row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch();
            $totalRows_rsproject_dissegragations = $query_rsproject_dissegragations->rowCount();

            do {
                $rowno = $opstate . $st . $row;
                $team = '<option value="" >...Select from list...</option>';
                $locationName = $row_rsproject_dissegragations['name'];
                $value = $row_rsproject_dissegragations['value'];

                if ($value > 0) {
                    $row++;
                    $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
                    $query_rsMembers->execute(array(":projid" => $projid));
                    $row_rsMembers = $query_rsMembers->fetch();
                    $totalRows_rsMembers = $query_rsMembers->rowCount();

                    do {
                        $ptid = $row_rsMembers['ptid'];
                        $query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
                        $query_rsTeam->execute(array(":ptid" => $ptid));
                        $row_rsTeam = $query_rsTeam->fetch();
                        $totalRows_rsTeam = $query_rsTeam->rowCount();

                        $team .= '<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
                    } while ($row_rsMembers = $query_rsMembers->fetch());

                    $data .= '
                    <tr id=""> 
                        <td>' . $st . "." . $row . '</td> 
                        <td>
                        ' . $locationName . '
                        <input type="hidden" name="diss_id' . $opstate . '[]" id="diss_id' . $rowno . '" class="form-control" value="' . $row_rsproject_dissegragations['id'] . '"  required />
                        </td>
                        <td>
                        <select name="team' . $opstate . '[]"  id="team' . $rowno . '" class="form-control" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                ' . $team . '
                            </select>
                        </td>  
                    </tr>';
                }
            } while ($row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch());

        } while ($row_rsdissegragations = $query_rsdissegragations->fetch());

    }else{
        $query_rsproject_dissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid	='$opid' AND projid='$projid'");
        $result = $query_rsproject_dissegragations->execute();
        $row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch();
        $totalRows_rsproject_dissegragations = $query_rsproject_dissegragations->rowCount();

        if($totalRows_rsproject_dissegragations > 0){
            $row =0;
            do { 
                $team = '<option value="" >...Select from list...</option>';
                $forest = $row_rsproject_dissegragations['outputstate']; 
                $diss_id = $row_rsproject_dissegragations['id'];

                $query_rsForest = $db->prepare("SELECT id, state FROM tbl_state WHERE  id='$forest' LIMIT 1");
                $query_rsForest->execute();
                $row_rsForest = $query_rsForest->fetch();
                $locationName = $row_rsForest['state'];
 
                    $row++;
                    $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
                    $query_rsMembers->execute(array(":projid" => $projid));
                    $row_rsMembers = $query_rsMembers->fetch();
                    $totalRows_rsMembers = $query_rsMembers->rowCount();

                    do {
                        $ptid = $row_rsMembers['ptid'];
                        $query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
                        $query_rsTeam->execute(array(":ptid" => $ptid));
                        $row_rsTeam = $query_rsTeam->fetch();
                        $totalRows_rsTeam = $query_rsTeam->rowCount();
                        $team .= '<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
                    } while ($row_rsMembers = $query_rsMembers->fetch());

                    $data .= '
                    <tr id=""> 
                        <td>' . $row . '</td> 
                        <td>
                        ' . $locationName . '
                        <input type="hidden" name="diss_id[]" id="diss_id' . $row . '" class="form-control" value="' . $row_rsproject_dissegragations['id'] . '"  required />
                        <input type="hidden" name="ind_forest[]" id="ind_forest' . $row . '" class="form-control" value=""  required />
                        </td>
                        <td> 
                        <select name="team'.$diss_id.'[]"  id="team' . $row . '" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                ' . $team . '
                            </select>
                        </td>  
                    </tr>'; 
            } while ($row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch());
        }
    }
    echo $data;
}


if (isset($_POST['get_frequency'])) {
    $opid = $_POST['get_frequency'];
      
    $query_rs_level =  $db->prepare("SELECT fq.level as level1 FROM tbl_datacollectionfreq as fq INNER JOIN tbl_project_details as p ON p.workplan_interval=fq.fqid WHERE p.id=:opid");
    $query_rs_level->execute(array(":opid" => $opid));
    $row_rs_level = $query_rs_level->fetch();
    $totalRows_rs_level = $query_rs_level->rowCount();
    $level =  $row_rs_level['level1']; 

 
    $query_rs_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where level <=:level");
    $query_rs_frequency->execute(array(":level" => $level));
    $row_rs_frequency = $query_rs_frequency->fetch();
    $totalRows_rs_frequency = $query_rs_frequency->rowCount();

    $input = '<option value="">... Select from list ...</option>';
    if ($totalRows_rs_frequency > 0) {
        do {
            $input .= '<option value="' . $row_rs_frequency['fqid'] . '">' . $row_rs_frequency['frequency'] . ' </option>';
        } while ($row_rs_frequency = $query_rs_frequency->fetch());
    } else {
        $input .= '<option value="">No Frequency Found</option>';
    }
    echo $input;
}


if (isset($_POST['get_reportingtimeline'])) {
    $fqid = $_POST['get_reportingtimeline'];
    $query_rs_level =  $db->prepare("SELECT  level FROM tbl_datacollectionfreq  WHERE fqid=:fqid");
    $query_rs_level->execute(array(":fqid" => $fqid));
    $row_rs_level = $query_rs_level->fetch();
    $totalRows_rs_level = $query_rs_level->rowCount();
    $level =  $row_rs_level['level']; 

    $query_rs_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where level >=:level");
    $query_rs_frequency->execute(array(":level" => $level));
    $row_rs_frequency = $query_rs_frequency->fetch();
    $totalRows_rs_frequency = $query_rs_frequency->rowCount();


    $input = '<option value="">... Select from list ...</option>';
    if ($totalRows_rs_frequency > 0) {
        do {
            $input .= '<option value="' . $row_rs_frequency['fqid'] . '">' . $row_rs_frequency['frequency'] . ' </option>';
        } while ($row_rs_frequency = $query_rs_frequency->fetch());
    } else {
        $input .= '<option value="">No Frequency Found</option>';
    }
    echo $input;
}



if(isset($_POST['get_limits'])){
        $type = $_POST['type'];
        $valid = []; 
        $valid['success'] = true; 

        if($type ==1){
            $query_rs_limit =  $db->prepare("SELECT * FROM tbl_datacollection_settings WHERE type=1");
            $query_rs_limit->execute();
            $row_rs_limit = $query_rs_limit->fetch();
            $totalRows_rs_limit = $query_rs_limit->rowCount();
            $impact_assessment = $row_rs_limit['fvalue']; 
            $valid['assessment'] = $impact_assessment;
        }else if($type ==2){
            $query_rs_limit =  $db->prepare("SELECT * FROM tbl_datacollection_settings WHERE type=2");
            $query_rs_limit->execute();
            $row_rs_limit = $query_rs_limit->fetch();
            $totalRows_rs_limit = $query_rs_limit->rowCount();
            $outcome_evaluation = $row_rs_limit['fvalue']; 
            $valid['assessment'] = $outcome_evaluation; 
        } 
     
    echo json_encode($valid);
}