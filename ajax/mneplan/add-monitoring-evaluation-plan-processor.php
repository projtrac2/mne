<?php
include '../controller.php';

$datecreated  = date("Y-m-d");
if (isset($_POST['get_risks'])) {
    $risk_type = $_POST['get_risks'];

    $query_rsRisk =  $db->prepare("SELECT * FROM tbl_projrisk_categories");
    $query_rsRisk->execute();
    $row_rsRisk = $query_rsRisk->fetch();
    $totalRows_rsRisk = $query_rsRisk->rowCount();
    $input = '<option value="">... Select from list ...</option>';

    if ($totalRows_rsRisk > 0) {
        do {
            $type = explode(',', $row_rsRisk['type']);
            if (in_array($risk_type, $type)) {
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
    $query_Indicator_cal->execute(array(':calcid' => $calcid));
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

//======================================== START ADD IMPACT DETAILS ============================================
if (isset($_POST['addimpact'])) {
    if (isset($_POST['impact']) && !empty($_POST['impact'])) {
        $impact = $_POST['impact'];
        $projid = $_POST['projid'];
        $indid  = $_POST['impactIndicator'];
        $data_source  = $_POST['impactdataSource'];
        $evaluation_frequency  = $_POST['impact_frequency'];
        $number_of_evaluations  = $_POST['evaluationNumberFreq'];
        $createdby  = $_POST['user_name'];

        $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_expected_impact_details`(projid, impact, indid, data_source, evaluation_frequency, number_of_evaluations, added_by, date_added) VALUES(:projid, :impact, :indid, :data_source, :evaluation_frequency, :number_of_evaluations, :added_by, :date_added)");
        $result1  = $insertSQL1->execute(array(":projid" => $projid, ":impact" => $impact, ":indid" => $indid, ":data_source" => $data_source, ":evaluation_frequency" => $evaluation_frequency, ":number_of_evaluations" => $number_of_evaluations, ":added_by" => $createdby, ":date_added" => $datecreated));
        $resultstypeid = $db->lastInsertId();

        $mainquestion = $_POST['impactmainquestion'];
        $questiontype = 1;
        $answertype = $_POST['impactmainanswertype'];
        $mainanswerlabels = $_POST['impact_main_answer_labels'];
        $resultstype = 1;


        if ($data_source == 1) {
            $insertmainquestion = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
            $result1  = $insertmainquestion->execute(array(":projid" => $projid, ":question" => $mainquestion, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $mainanswerlabels));

            for ($j = 0; $j < count($_POST['impactquestions']); $j++) {
                if (!empty($_POST['impactquestions'][$j]) && !empty($_POST['impactanswertype'][$j])) {
                    $question = $_POST['impactquestions'][$j];
                    $answertype = $_POST['impactanswertype'][$j];
                    $answerlabels = $_POST['impact_other_answer_label'][$j];
                    $questiontype = 2;

                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
                    $result1  = $insertSQL1->execute(array(":projid" => $projid, ":question" => $question, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $answerlabels));
                }
            }
        }

        if ($result1) {
            $msg = 'Project Impact evaluation data successfully added';
            echo json_encode(array("success" => true, "msg" => $msg));
        }
    }
}

//======================================== END ADD IMPACT DETAILS ===============================================

//======================================== START EDIT IMPACT DETAILS ============================================
if (isset($_POST['editimpact'])) {
    if (isset($_POST['impactid']) && !empty($_POST['impactid'])) {
        $impactid = $_POST['impactid'];
        $impact = $_POST['impact'];
        $projid = $_POST['projid'];
        $indid  = $_POST['impactIndicator'];
        $data_source  = $_POST['impactdataSource'];
        $evaluation_frequency  = $_POST['impact_frequency'];
        $number_of_evaluations  = $_POST['evaluationNumberFreq'];
        $mainanswerlabels = $_POST['impact_main_answer_labels'];
        $createdby  = $_POST['user_name'];

        $insertSQL1 = $db->prepare("UPDATE `tbl_project_expected_impact_details`  SET projid = :projid, impact = :impact, indid = :indid, data_source = :data_source, evaluation_frequency = :evaluation_frequency, number_of_evaluations = :number_of_evaluations, changed_by = :added_by, date_changed = :date_added WHERE id = :impactid");
        $result1  = $insertSQL1->execute(array(":projid" => $projid, ":impact" => $impact, ":indid" => $indid, ":data_source" => $data_source, ":evaluation_frequency" => $evaluation_frequency, ":number_of_evaluations" => $number_of_evaluations, ":added_by" => $createdby, ":date_added" => $datecreated, ":impactid" => $impactid));

        $mainquestion = $_POST['impactmainquestion'];
        $questiontype = 1;
        $answertype = $_POST['impactmainanswertype'];
        $resultstype = 1;

        $deleteQuery = $db->prepare("DELETE FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstypeid=:impactid");
        $results1 = $deleteQuery->execute(array(':projid' => $projid, ':impactid' => $impactid));

        if ($data_source == 1) {
            $insertmainquestion = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
            $result1  = $insertmainquestion->execute(array(":projid" => $projid, ":question" => $mainquestion, ":resultstype" => $resultstype, ":resultstypeid" => $impactid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $mainanswerlabels));

            for ($j = 0; $j < count($_POST['impactquestions']); $j++) {
                $question = $_POST['impactquestions'][$j];
                $answertype = $_POST['impactanswertype'][$j];
                $answerlabels = $_POST['impact_other_answer_label'][$j];
                $questiontype = 2;

                if (!empty($question)) {
                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
                    $result1  = $insertSQL1->execute(array(":projid" => $projid, ":question" => $question, ":resultstype" => $resultstype, ":resultstypeid" => $impactid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $answerlabels));
                }
            }
        }

        if ($result1) {
            $msg = 'Project Impact evaluation data successfully editted';
            echo json_encode(array("success" => true, "msg" => $msg));
        }
    }
}


//======================================== EDIT EDIT IMPACT DETAILS ============================================


//======================================== START ADD outcome DETAILS ============================================

if (isset($_POST['addoutcome'])) {
    if (isset($_POST['outcome']) && !empty($_POST['outcome'])) {
        $outcome = $_POST['outcome'];
        $projid = $_POST['projid'];
        $indid  = $_POST['outcomeIndicator'];
        $data_source  = $_POST['outcomedataSource'];
        $evaluation_frequency  = $_POST['outcome_frequency'];
        $number_of_evaluations  = $_POST['evaluationNumberFreq'];
        $mainanswerlabels = $_POST['outcome_main_answer_labels'];
        $createdby  = $_POST['user_name'];
        $parentimpact  = 0;
        if (isset($_POST['parentimpact']) && !empty($_POST['parentimpact'])) {
            $parentimpact  = $_POST['parentimpact'];
        }

        $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_expected_outcome_details`(projid, impactid, outcome, indid, data_source, evaluation_frequency, number_of_evaluations, added_by, date_added) VALUES(:projid, :parentimpact, :outcome, :indid, :data_source, :evaluation_frequency, :number_of_evaluations, :added_by, :date_added)");
        $result1  = $insertSQL1->execute(array(":projid" => $projid, ":parentimpact" => $parentimpact, ":outcome" => $outcome, ":indid" => $indid, ":data_source" => $data_source, ":evaluation_frequency" => $evaluation_frequency, ":number_of_evaluations" => $number_of_evaluations, ":added_by" => $createdby, ":date_added" => $datecreated));
        $resultstypeid = $db->lastInsertId();

        $answertype = $_POST['outcomemainanswertype'];
        $mainquestion = $_POST['outcomemainquestion'];
        $questiontype = 1;
        $resultstype = 2;

        if ($data_source == 1) {
            $insertmainquestion = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
            $result1  = $insertmainquestion->execute(array(":projid" => $projid, ":question" => $mainquestion, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $mainanswerlabels));

            if(isset($_POST['outcomeotheranswertype']) && $_POST['outcomeotheranswertype'] != '' && isset($_POST['outcomeotheranswertype'])){
                for ($j = 0; $j < count($_POST['outcomeotherquestions']); $j++) {
                    $question = $_POST['outcomeotherquestions'][$j];
                    $answertype = $_POST['outcomeotheranswertype'][$j];
                    $answerlabels = $_POST['outcome_other_answer_label'][$j];
                    if($question !='' && $answertype !='' && $answerlabels !=''){
                        $questiontype = 2;
                        $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
                        $result1  = $insertSQL1->execute(array(":projid" => $projid, ":question" => $question, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $answerlabels));
                    }
                }
            }
        }

        if ($result1) {
            $msg = 'Project Outcome evaluation data successfully added';
            echo json_encode(array("success" => true, "msg" => $msg));
        }
    }
}

//================================== END ADD OUTCOME DETAILS ================================================



//======================================== START EDIT outcome DETAILS ============================================

if (isset($_POST['editoutcome'])) {
    if (isset($_POST['outcomeid']) && !empty($_POST['outcomeid'])) {
        $outcomeid = $_POST['outcomeid'];
        $outcome = $_POST['outcome'];
        $projid = $_POST['projid'];
        $indid  = $_POST['outcomeIndicator'];
        $data_source  = $_POST['outcomedataSource'];
        $evaluation_frequency  = $_POST['outcome_frequency'];
        $number_of_evaluations  = $_POST['evaluationNumberFreq'];
        $mainanswerlabels = $_POST['outcome_main_answer_labels'];
        $createdby  = $_POST['user_name'];
        $parentimpact  = 0;
        if (isset($_POST['parentimpact']) && !empty($_POST['parentimpact'])) {
            $parentimpact  = $_POST['parentimpact'];
        }

        $insertSQL1 = $db->prepare("UPDATE `tbl_project_expected_outcome_details`  SET projid = :projid, impactid = :parentimpact, outcome = :outcome, indid = :indid, data_source = :data_source, evaluation_frequency = :evaluation_frequency, number_of_evaluations = :number_of_evaluations, changed_by = :changed_by, date_changed = :date_added WHERE id = :outcomeid");
        $result1  = $insertSQL1->execute(array(":projid" => $projid, ":parentimpact" => $parentimpact, ":outcome" => $outcome, ":indid" => $indid, ":data_source" => $data_source, ":evaluation_frequency" => $evaluation_frequency, ":number_of_evaluations" => $number_of_evaluations, ":changed_by" => $createdby, ":date_added" => $datecreated, ":outcomeid" => $outcomeid));

        $mainquestion = $_POST['outcomemainquestion'];
        $questiontype = 1;
        $answertype = $_POST['outcomemainanswertype'];
        $resultstype = 2;

        $deleteQuery = $db->prepare("DELETE FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstypeid=:outcomeid");
        $results1 = $deleteQuery->execute(array(':projid' => $projid, ':outcomeid' => $outcomeid));

        if ($data_source == 1) {
            $insertmainquestion = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
            $insertmainquestion->execute(array(":projid" => $projid, ":question" => $mainquestion, ":resultstype" => $resultstype, ":resultstypeid" => $outcomeid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $mainanswerlabels));

            for ($j = 0; $j < count($_POST['outcomeotherquestions']); $j++) {
                $question = $_POST['outcomeotherquestions'][$j];
                $answertype = $_POST['outcomeotheranswertype'][$j];
                $answerlabels = $_POST['outcome_other_answer_label'][$j];
                $questiontype = 2;

                if (!empty($question)) {
                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_evaluation_questions`(projid, question, resultstype, resultstypeid, questiontype, answertype, answerlabels) VALUES(:projid, :question, :resultstype, :resultstypeid, :questiontype, :answertype, :answerlabels)");
                    $insertSQL1->execute(array(":projid" => $projid, ":question" => $question, ":resultstype" => $resultstype, ":resultstypeid" => $outcomeid, ":questiontype" => $questiontype, ":answertype" => $answertype, ":answerlabels" => $answerlabels));
                }
            }
        }

        if ($result1) {
            $msg = 'Project Outcome evaluation data successfully editted';
            echo json_encode(array("success" => true, "msg" => $msg));
        }
    }
}

//================================== END EDIT OUTCOME DETAILS ================================================


//================================== START ADD OUTPUT DETAILS ================================================
if (isset($_POST['addoutput'])) {
    if (isset($_POST['opid'])) {
        $output = $_POST['opid'];
        $projid = $_POST['projid'];
        $outputIndicator  = $_POST['output_indicator'];
        $outcomeid  = 0;
        if (isset($_POST['parentoutcome']) && !empty($_POST['parentoutcome'])) {
            $outcomeid = $_POST['parentoutcome'];
        }

        $datecreated = date("Y-m-d");
        $createdby = $_POST['user_name'];
        $mnecode = "AB123" . $projid . $output;
        $sql = $db->prepare("INSERT INTO `tbl_project_outputs_mne_details`(projid,outcomeid,outputid,indicator,mne_code,date_created,created_by) VALUES(:projid,:outcomeid,:output,:indicator,:mnecode,:date_added,:added_by)");
        $result1  = $sql->execute(array(":projid" => $projid, ":outcomeid" => $outcomeid, ":output" => $output, ":indicator" => $outputIndicator,":mnecode" => $mnecode, ":date_added" => $datecreated, ":added_by" => $createdby));

        if (isset($_POST['outputrisk'])) {
            for ($i = 0; $i < count($_POST['outputrisk']); $i++) {
                $riskid = $_POST['outputrisk'][$i];
                $assumption = $_POST['output_assumptions'][$i];
                $type = 3;
                $insertSQL1 = $db->prepare("INSERT INTO `tbl_projectrisks`(projid, rskid, outputid, type, assumption) VALUES(:projid, :rskid, :outputid, :type, :assumption )");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":rskid" => $riskid, ":outputid" => $output, ":type" => $type, ":assumption" => $assumption));
            }
        }

        $output_team = 3;
        $project_team_stage = 7;
        $members = $_POST['member'];
        $roles = $_POST['role'];
        $general_substage = 3;

        $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND stage=:stage AND sub_stage=:sub_stage");
        $result = $sql->execute(array(':projid' => $projid, ":stage" => $project_team_stage, ":sub_stage" => $output_team));
        $total_members = count($members);
        for ($i = 0; $i < $total_members; $i++) {
            $role = $roles[$i];
            $ptid = $members[$i];
            $insertSQL = $db->prepare("INSERT INTO tbl_projmembers (projid,role,stage,sub_stage,responsible,created_by,created_at) VALUES (:projid,:role,:stage,:sub_stage,:responsible,:created_by,:created_at)");
            $result = $insertSQL->execute(array(':projid' => $projid, ':role' => $role, ":stage" => $project_team_stage, ':sub_stage' => $general_substage, ':responsible' => $ptid, ':created_by' => $user_name, ':created_at' => $today));
        }


        if ($result1) {
            echo json_encode(array("msg" => true));
        }
    }
}


//================================== START ADD OUTPUT DETAILS ================================================
if (isset($_POST['addprojectfrequency'])) {
    $frequency = $_POST['outputMonitorigFreq'];
    $projid = $_POST['projid'];
    $insertSQL1 = $db->prepare("UPDATE tbl_projects SET monitoring_frequency = :frequency WHERE  projid=:projid");
    $result1  = $insertSQL1->execute(array(":frequency" => $frequency,":projid" => $projid));
    echo json_encode(array('success'=>$result1));
}
//================================== END EDIT OUTCOME DETAILS ================================================

function next_monitoring_date($projid, $outputid, $frequency_id)
{
    global $db;
    $query_monitoringfreq =  $db->prepare("SELECT frequency, days FROM tbl_datacollectionfreq WHERE fqid = :fqid");
    $query_monitoringfreq->execute(array(":fqid" => $frequency_id));
    $row_monitoringfreq = $query_monitoringfreq->fetch();
    $totalRows_monitoringfreq = $query_monitoringfreq->rowCount();

    $next_mon_date = "";
    if ($totalRows_monitoringfreq > 0) {
        $monfreq = $row_monitoringfreq['days'];
        $query_task_startdate =  $db->prepare("SELECT MIN(sdate) as earliestopdate FROM tbl_task WHERE projid = :projid and outputid=:outputid");
        $query_task_startdate->execute(array(":projid" => $projid, ":outputid" => $outputid));
        $row_task_startdate = $query_task_startdate->fetch();
        $task_startdate = $row_task_startdate['earliestopdate'];
        $next_mon_date = date("Y-m-d", strtotime($task_startdate . ' + ' . $monfreq));
    }

    return $next_mon_date;
}


if (isset($_POST['get_output_details'])) {
    $projid = $_POST['projid'];
    $outputid = $_POST['opid'];
    $query_rsDetails =  $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid=:projid AND outputid=:outputid");
    $query_rsDetails->execute(array(":projid" => $projid, ":outputid" => $outputid));
    $row_rsDetails = $query_rsDetails->fetch();
    $totalRows_rsDetails = $query_rsDetails->rowCount();

    $success = false;
    $moni = '<option value="">No Frequency Found</option>';
    $responsible_input = '<option value="">... Select Personnel Found ...</option>';
    $risks = $monitoringfreq = $responsible = '';

    if ($totalRows_rsDetails > 0) {
        $success = true;
        $monitoringfreq = $row_rsDetails['monitoring_frequency'];
        $responsible = $row_rsDetails['responsible'];
        $opid = $row_rsDetails['outputid'];

        $query_rsOPRisk =  $db->prepare("SELECT * FROM tbl_projectrisks WHERE projid=:projid AND outputid=:outputid");
        $query_rsOPRisk->execute(array(":projid" => $projid, ":outputid" => $outputid));
        $row_rsOPRisk = $query_rsOPRisk->fetch();
        $totalRows_rsOPRisk = $query_rsOPRisk->rowCount();

        if ($totalRows_rsOPRisk > 0) {
            $rowno = 0;
            do {
                $type = 3;
                $riskid = $row_rsOPRisk['rskid'];
                $assumption = $row_rsOPRisk['assumption'];
                $rowno++;
                $query_rsRisk = $db->prepare("SELECT * FROM tbl_projrisk_categories");
                $query_rsRisk->execute();
                $totalRows_rsRisk = $query_rsRisk->rowCount();

                $input = '<option value="">No Risks Found</option>';
                if ($totalRows_rsRisk > 0) {
                    $input = '<option value="">... Select from list ...</option>';
                    while ($row_rsRisk = $query_rsRisk->fetch()) {
                        $risktype = explode(',', $row_rsRisk["type"]);
                        if (in_array($type, $risktype)) {
                            $selected = ($riskid == $row_rsRisk['rskid']) ? "selected" : "";
                            $input .= '<option value="' . $row_rsRisk['rskid'] . '" ' . $selected . '>' . $row_rsRisk['category'] . ' </option>';
                        }
                    }
                }

                $risks .= '
                <tr id="outputrow' . $rowno . '">
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
                if ($rowno != 1) {
                    $risks .= '
                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_output("outputrow' . $rowno . '")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>';
                }
                $risks .= '
                    </td>
                </tr>';
            } while ($row_rsOPRisk = $query_rsOPRisk->fetch());
        }
    }

    echo json_encode(array("monitoringfreq" => $monitoringfreq, "responsible" => $responsible, "risks" => $risks, "detailscount" => $totalRows_rsDetails));
}

if (isset($_POST['editoutput'])) {
    $opid = $_POST['opid'];
    $projid = $_POST['projid'];
    $outputMonitorigFreq = $_POST['outputMonitorigFreq'];
    $datecreated = date("Y-m-d");
    $createdby = $_POST['user_name'];
    $daysbefore = $_POST['daysbefore'];
    $daysafter = $_POST['daysafter'];
    $next_mon_date = next_monitoring_date($projid, $opid, $outputMonitorigFreq);
    $insertSQL1 = $db->prepare("UPDATE tbl_project_outputs_mne_details SET monitoring_frequency=:monitoring_frequency,daysbefore=:daysbefore, daysafter=:daysafter, next_monitoring_date=:next_monitoring_date, date_changed=:date_added, changed_by=:added_by WHERE outputid=:opid ");
    $result1  = $insertSQL1->execute(array(":monitoring_frequency" => $outputMonitorigFreq, ":daysbefore" => $daysbefore, ":daysafter" => $daysafter, ':next_monitoring_date' => $next_mon_date, ":date_added" => $datecreated, ":added_by" => $createdby, ":opid" => $opid));


    $output_team = 3;
    $project_team_stage = 7;
    $general_substage = 3;
    $members = $_POST['member'];
    $roles = $_POST['role'];

    $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND stage=:stage AND sub_stage=:sub_stage");
    $result = $sql->execute(array(':projid' => $projid, ":stage" => $project_team_stage, ":sub_stage" => $output_team));
    $total_members = count($members);
    for ($i = 0; $i < $total_members; $i++) {
        $role = $roles[$i];
        $ptid = $members[$i];
        $insertSQL = $db->prepare("INSERT INTO tbl_projmembers (projid,role,stage,sub_stage,responsible,created_by,created_at) VALUES (:projid,:role,:stage,:sub_stage,:responsible,:created_by,:created_at)");
        $result = $insertSQL->execute(array(':projid' => $projid, ':role' => $role, ":stage" => $project_team_stage, ':sub_stage' => $general_substage, ':responsible' => $ptid, ':created_by' => $user_name, ':created_at' => $today));
    }

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
    }
    echo json_encode(array("msg" => true));
}

if (isset($_POST["deleteItem"])) {
    $itemid = $_POST['itemId'];
    $responsible = NULL;
    $report_user = NULL;
    $insertSQL1 = $db->prepare("UPDATE tbl_projects SET mne_responsible = :responsible, mne_report_users=:reportUsers WHERE  projid=:projid");
    $result1  = $insertSQL1->execute(array(":responsible" => $responsible, ":reportUsers" => $report_user, ":projid" => $itemid));

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
                    $query_rsTeam = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE directorate=42");
                    $query_rsTeam->execute();
                    $row_rsTeam = $query_rsTeam->fetch();
                    $totalRows_rsTeam = $query_rsTeam->rowCount();

                    if ($totalRows_rsTeam > 0) {
                        do {
                            $team .= '<option value="' . $row_rsTeam['userid'] . '">' . $row_rsTeam['fullname'] . '</option>';
                        } while ($row_rsTeam = $query_rsTeam->fetch());
                    }

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
    } else {
        $query_rsproject_dissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid	='$opid' AND projid='$projid'");
        $result = $query_rsproject_dissegragations->execute();
        $row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch();
        $totalRows_rsproject_dissegragations = $query_rsproject_dissegragations->rowCount();

        if ($totalRows_rsproject_dissegragations > 0) {
            $row = 0;
            do {
                $team = '<option value="" >...Select from list...</option>';
                $forest = $row_rsproject_dissegragations['outputstate'];
                $diss_id = $row_rsproject_dissegragations['id'];

                $query_rsForest = $db->prepare("SELECT id, state FROM tbl_state WHERE  id='$forest' LIMIT 1");
                $query_rsForest->execute();
                $row_rsForest = $query_rsForest->fetch();
                $locationName = $row_rsForest['state'];

                $row++;
                $query_rsMembers = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE directorate=42");
                $query_rsMembers->execute();
                $totalRows_rsMembers = $query_rsMembers->rowCount();

                if ($totalRows_rsMembers > 0) {
                    while ($row_rsMembers = $query_rsMembers->fetch()) {
                        $team .= '<option value="' . $row_rsMembers['userid'] . '">' . $row_rsMembers['fullname'] . '</option>';
                    }
                }

                $data .= '
                    <tr id="">
                        <td>' . $row . '</td>
                        <td>
                        ' . $locationName . '
                        <input type="hidden" name="diss_id[]" id="diss_id' . $row . '" class="form-control" value="' . $row_rsproject_dissegragations['id'] . '"  required />
                        <input type="hidden" name="ind_forest[]" id="ind_forest' . $row . '" class="form-control" value=""  required />
                        </td>
                        <td>
                        <select name="team' . $diss_id . '[]"  id="team' . $row . '" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
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

    /* $query_rs_level =  $db->prepare("SELECT fq.level as level1 FROM tbl_datacollectionfreq as fq INNER JOIN tbl_project_details as p ON p.workplan_interval=fq.fqid WHERE p.id=:opid");
    $query_rs_level->execute(array(":opid" => $opid));
    $row_rs_level = $query_rs_level->fetch();
    $totalRows_rs_level = $query_rs_level->rowCount();
    $level =  $row_rs_level['level1']; */

    $query_rs_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where status =1");
    $query_rs_frequency->execute();
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



if (isset($_POST['get_limits'])) {
    $type = $_POST['type'];
    $valid = [];
    $valid['success'] = true;

    if ($type == 1) {
        $query_rs_limit =  $db->prepare("SELECT * FROM tbl_datacollection_settings WHERE type=1");
        $query_rs_limit->execute();
        $row_rs_limit = $query_rs_limit->fetch();
        $totalRows_rs_limit = $query_rs_limit->rowCount();
        $impact_assessment = $row_rs_limit['fvalue'];
        $valid['assessment'] = $impact_assessment;
    } else if ($type == 2) {
        $query_rs_limit =  $db->prepare("SELECT * FROM tbl_datacollection_settings WHERE type=2");
        $query_rs_limit->execute();
        $row_rs_limit = $query_rs_limit->fetch();
        $totalRows_rs_limit = $query_rs_limit->rowCount();
        $outcome_evaluation = $row_rs_limit['fvalue'];
        $valid['assessment'] = $outcome_evaluation;
    }

    echo json_encode($valid);
}
