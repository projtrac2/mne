<?php
include_once "controller.php";
try {
    if (isset($_POST['projcode'])) {
        $data = $_POST['projcode'];
        $query_rsProject = $db->prepare("SELECT projcode FROM tbl_projects WHERE projcode=:data");
        $result = $query_rsProject->execute(array(":data" => $data));
        $row_rsProject = $query_rsProject->fetch();
        $totalRows_rsProject = $query_rsProject->rowCount();
        if ($result) {
            if ($totalRows_rsProject > 0) {
                echo json_encode("true");
            } else {
                echo json_encode("false");
            }
        } else {
            echo json_encode("false");
        }
    }

    if (isset($_POST['getward'])) {
        $getward = explode(",", $_POST['getward']);
        $data = '';
        for ($i = 0; $i < count($getward); $i++) {
            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id=:getward LIMIT 1");
            $query_rsComm->execute(array(":getward" => $getward[$i]));
            $row_rsComm = $query_rsComm->fetch();
            $community = $row_rsComm['state'];

            $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:getward");
            $query_ward->execute(array(":getward" => $getward[$i]));
            $data .= '
            <optgroup label="' . $community . '"> ';
            while ($row = $query_ward->fetch()) {
                $projlga = $row['id'];
                $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                $query_rsLocations->execute(array(":id" => $projlga));
                $row_rsLocations = $query_rsLocations->fetch();
                $total_locations = $query_rsLocations->rowCount();
                if ($total_locations > 0) {
                    $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
                }
            }
            $data .= '
            <optgroup>';
        }
        echo $data;
    }

    if (isset($_POST['getlocation'])) {
        $getlocation = explode(",", $_POST['getlocation']);
        $data = '';
        for ($j = 0; $j < count($getlocation); $j++) {
            $query_ward = $db->prepare("SELECT id, state, parent FROM tbl_state WHERE id='$getlocation[$j]'");
            $query_ward->execute();
            $row_ward = $query_ward->fetch();
            $ward = $row_ward['state'];
            $parent = $row_ward['parent'];

            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id='$parent' LIMIT 1");
            $query_rsComm->execute();
            $row_rsComm = $query_rsComm->fetch();
            $community = $row_rsComm['state'];

            $query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$getlocation[$j]'");
            $query_loca->execute();
            $data .= '<optgroup label="' . $ward . ' (' .  $community . ')">';
            while ($row = $query_loca->fetch()) {
                $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
            }

            $data .= '<optgroup>';
        }
        echo $data;
    }

    if (isset($_POST['get_location'])) {
        $getlocation = explode(",", $_POST['get_location']);
        $data = '<option value="">.... Select from list ....</option>';
        for ($j = 0; $j < count($getlocation); $j++) {
            $query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE id='$getlocation[$j]'");
            $query_loca->execute();
            while ($row = $query_loca->fetch()) {
                $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
            }
        }
        echo $data;
    }

    //GET THE YEARS 
    if (isset($_POST['getyears'])) {
        $getyears = $_POST['getyears'];
        $query_years = $db->prepare("SELECT * FROM `tbl_strategic_plan_objectives` 
		INNER JOIN tbl_key_results_area ON tbl_key_results_area.id = tbl_strategic_plan_objectives.kraid
		INNER JOIN tbl_strategicplan ON tbl_strategicplan.id = tbl_key_results_area.spid
		WHERE tbl_strategic_plan_objectives.id ='$getyears' LIMIT 1 ");
        $query_years->execute();
        $row = $query_years->fetch();

        $years = $row['years'];
        $startyear = $row['starting_year'];
        $output = array("startyear" => $startyear, "years" => $years);
        echo json_encode($output);
    }

    //GET THE financial year
    if (isset($_POST['getyear'])) {
        $getyear = $_POST['getyear'];
        $query_year = $db->prepare("SELECT yr  FROM  tbl_fiscal_year WHERE id='$getyear' LIMIT 1 ");
        $query_year->execute();
        $row = $query_year->fetch();
        $year = $row['yr'];
        echo json_encode($year);
    }

    if (isset($_POST['getOutputTable'])) {
        echo    '<div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="output_table" style="width:100%">
                        <thead>
                            <tr>
                                <th width="3%">#</th>
                                <th width="47%">Output</th>
                                <th width="30%">Indicator</th>
                                <th width="15%">Other Details</th>
                                <th width="5%">
                                    <button type="button" name="addplus" id="addplus_output" onclick="add_row_output();" class="btn btn-success btn-sm">
                                        <span class="glyphicon glyphicon-plus">
                                        </span>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="output_table_body">
                        <tr></tr>
                        <tr id="hideinfo">
                            <td colspan="5">Add Output!!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>';
    }

    if (isset($_POST['getIndicator'])) {
        $indicator =  $_POST['getIndicator'];
        $progid =  $_POST['getIndicatorProgid'];
        $query_Indicator = $db->prepare("SELECT indid, indicator_name, indicator_disaggregation, indicator_unit FROM tbl_progdetails INNER JOIN tbl_indicator ON tbl_indicator.indid = tbl_progdetails.indicator WHERE progid ='$progid' AND id='$indicator'");
        $query_Indicator->execute();
        $row_rsInd = $query_Indicator->fetch();

        $indid = $row_rsInd['indid'];
        $indicator_name = $row_rsInd['indicator_name'];
        $ben_diss = $row_rsInd['indicator_disaggregation'];
        $diss_type_op = 'Location';
        $unit = $row_rsInd['indicator_unit'];

        $query_rsunit = $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unit' ");
        $query_rsunit->execute();
        $row_rsunit = $query_rsunit->fetch();
        $opunit = $row_rsunit['unit'];

        echo json_encode(array("indid" => $indid,  "indicator_name" => $indicator_name, "ben_diss" => $ben_diss, "diss_type_op" => $diss_type_op, "unit" => $opunit));
    }

    if (isset($_POST['getprojoutput'])) {
        $progid = $_POST['outProgid'];
        $projstartYear = $_POST['outprojstartYear'];
        $duration = $_POST['outduration'];
        $outputId = [];

        for ($i  = 0; $i < $duration; $i++) {
            $query_Target = $db->prepare("SELECT * FROM `tbl_progdetails` WHERE progid ='$progid' and  year ='$projstartYear'");
            $query_Target->execute();
            $row_Target = $query_Target->fetch();
            $totalRows_Target = $query_Target->rowCount();

            do {
                $progTarget = $row_Target['target'];
                $projoutputid = $row_Target['indicator'];
                $projoutput = $row_Target['output'];

                $query_projTarget = $db->prepare("SELECT SUM(target) as projtarget FROM  tbl_project_output_details WHERE progid='$progid' AND indicator ='$projoutputid' and year='$projstartYear'");
                $query_projTarget->execute();
                $rowproj = $query_projTarget->fetch();
                $totalRows_Target = $query_projTarget->rowCount();

                $totalUsedTarget  =  $rowproj['projtarget'];
                $projTarget = $progTarget - $totalUsedTarget;
                if ($projTarget > 0) {
                    $outputId[] = $projoutputid;
                }
            } while ($row_Target = $query_Target->fetch());

            $projstartYear++;
        }

        $input = '';
        $input .= '<option value="">Select Output</option>';
        $outputId = array_unique($outputId);

        foreach ($outputId as $indicatorid) {
            // get financial years 
            $query_rsYear =  $db->prepare("SELECT id, output FROM tbl_progdetails where progid='$progid' AND indicator ='$indicatorid'");
            $query_rsYear->execute();
            $row_rsYear = $query_rsYear->fetch();
            $projoutput = $row_rsYear['output'];
            $opid = $row_rsYear['id'];
            $input .= '<option value="' . $opid . '">' . $projoutput . '</option>';
        }
        echo $input;
    }

    if (isset($_POST['projoutputYear'])) {
        $progid = $_POST['opprogid'];
        if (isset($_POST['opprojid'])) {
            $projid = $_POST['opprojid'];
        }

        $outputid = $_POST['opstartoutputId'];
        $projstartYear = $_POST['opprojstartYear'];
        $projendYear = $_POST['opprojendYear'];
        $duration = $_POST['opduration'];
        $financialYears = [];

        for ($i  = 0; $i < $duration; $i++) {
            $query_Target = $db->prepare("SELECT * FROM `tbl_progdetails` WHERE progid ='$progid' and year ='$projstartYear' and indicator ='$outputid'");
            $query_Target->execute();
            $row_Target = $query_Target->fetch();
            $totalRows_Target = $query_Target->rowCount();

            do {
                $progTarget = $row_Target['target'];
                $year = $row_Target['year'];
                $indicator = $row_Target['indicator'];
                $amountprogBudget = $row_Target['budget'];


                $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE yr='$year'");
                $query_rsYear->execute();
                $row_rsYear = $query_rsYear->fetch();
                $fscyear = $row_rsYear['id'];

                $query_projTarget = $db->prepare("SELECT SUM(target) as projtarget FROM tbl_project_output_details WHERE progid='$progid' AND indicator ='$indicator' and year='$year' AND projid IS NOT NULL");
                $query_projTarget->execute();
                $rowproj = $query_projTarget->fetch();
                $totalRows_Target = $query_projTarget->rowCount();

                $project_used_target = $project_used_budget = 0;

                if (isset($_POST['opprojid'])) {
                    $query_projTarget1 = $db->prepare("SELECT SUM(target) as projtarget FROM tbl_project_output_details WHERE projid='$projid' AND indicator ='$indicator' and year='$year' AND projid IS NOT NULL");
                    $query_projTarget1->execute();
                    $rowproj1 = $query_projTarget1->fetch();
                    $totalRows_Target1 = $query_projTarget1->rowCount();
                    $project_used_target = $rowproj1['projtarget'];

                    $query_rsprojBudget1 = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details  WHERE indicator ='$indicator' and projid ='$projid' and year ='$fscyear' and projid IS NOT NULL");
                    $query_rsprojBudget1->execute();
                    $row_rsprojBudget1 = $query_rsprojBudget1->fetch();
                    $totalRows_rsprojBudget1 = $query_rsprojBudget1->rowCount();
                    $project_used_budget = $row_rsprojBudget1['budget'];
                }

                $query_rsprojBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details  WHERE indicator ='$indicator' and progid ='$progid' and year ='$fscyear' and projid IS NOT NULL");
                $query_rsprojBudget->execute();
                $row_rsprojBudget = $query_rsprojBudget->fetch();
                $totalRows_rsprojBudget = $query_rsprojBudget->rowCount();
                $amountprojBudget = $row_rsprojBudget['budget'];
                $remainingBudget =  $amountprogBudget - ($amountprojBudget - $project_used_budget);

                $totalUsedTarget  =  $rowproj['projtarget'];
                $projTargetbal = $progTarget - ($totalUsedTarget - $project_used_target);

                if ($projTargetbal > 0 && $remainingBudget > 0) {
                    $financialYears[] = $year;
                }
            } while ($row_Target = $query_Target->fetch());
            $projstartYear++;
        }

        $input = '';
        $input .= '<option value="">Select Year</option>';

        $financialYr = array_unique($financialYears);
        foreach ($financialYr as $financialyear) {
            // get financial years 
            $query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year where yr ='$financialyear'");
            $query_rsYear->execute();
            $row_rsYear = $query_rsYear->fetch();
            $finyear = $row_rsYear['year'];
            $finyearid = $row_rsYear['id'];
            $input .= '<option value="' . $finyearid . '">' . $finyear . '</option>';
        }
        echo $input;
    }

    if (isset($_POST['getoutputBudget'])) {
        $indicatorid = $_POST['indicatorid'];
        $programid = $_POST['programid'];
        $outputYear = $_POST['outputYear'];
        $outputduration = $_POST['outputduration'];

        $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id='$outputYear'");
        $query_rsYear->execute();
        $row_rsYear = $query_rsYear->fetch();
        $fscyear = $row_rsYear ?  $row_rsYear['yr'] : false;
        $target = $budget = 0;
        $msg = false;
        if ($fscyear) {
            $date = $fscyear . "-07-01";
            $newDate = date('Y-m-d', strtotime($date . ' + ' . $outputduration . ' days'));
            $endyear = date('Y', strtotime($newDate));

            $query_rsprogBudget = $db->prepare("SELECT  SUM(target) as target, SUM(budget) as budget FROM tbl_progdetails WHERE indicator ='$indicatorid' and progid ='$programid' and year >= '$fscyear' AND year <= '$endyear'");
            $query_rsprogBudget->execute();
            $row_rsprogBudget = $query_rsprogBudget->fetch();
            $totalRows_rsprogBudget = $query_rsprogBudget->rowCount();
            $program_budget = $row_rsprogBudget ? $row_rsprogBudget['budget'] : 0;
            $program_target = $row_rsprogBudget ? $row_rsprogBudget['target'] : 0;

            $query_rsprojTarget =  $db->prepare("SELECT SUM(target) as target, SUM(budget) as budget FROM tbl_project_output_details WHERE indicator ='$indicatorid' and progid ='$programid' and year >= '$fscyear'  AND year <= '$endyear'");
            $query_rsprojTarget->execute();
            $row_rsprojTarget = $query_rsprojTarget->fetch();
            $project_target = $row_rsprojTarget ? $row_rsprojTarget['target'] : 0;
            $project_budget = $row_rsprogBudget ? $row_rsprojTarget['budget'] : 0;

            $remaining_budget =  $program_budget - $project_budget;
            $remaining_target = $program_target - $project_target;

            $budget = $remaining_budget > 0 ? $remaining_budget : 0;
            $target = $remaining_target > 0 ? $remaining_target : 0;
            $msg = true;
        }
        echo json_encode(array("budget" => $budget, "target" => $target, "msg" => $msg));
    }


    if (isset($_POST['getTarget'])) {
        $indicatorid = $_POST['indicator'];
        $programid = $_POST['program'];
        $outputYear = $_POST['year'];
        $query_rsprogTarget =  $db->prepare("SELECT target FROM tbl_progdetails WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$outputYear'");
        $query_rsprogTarget->execute();
        $row_rsprogTarget = $query_rsprogTarget->fetch();
        $progTarget = $row_rsprogTarget['target'];

        $query_rsprojTarget =  $db->prepare("SELECT SUM(target) as target FROM tbl_project_output_details WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$outputYear' ");
        $query_rsprojTarget->execute();
        $row_rsprojTarget = $query_rsprojTarget->fetch();
        $projTarget = $row_rsprojTarget['target'];
        $remaining =  $progTarget - $projTarget;

        $arr = [];
        if ($remaining > 0) {
            $arr =   array("remaining" => $remaining, "msg" => "true");
        } else {
            $arr =   array("msg" => "false");
        }
        echo json_encode($arr);
    }

    if (isset($_POST['getfinancialYear'])) {
        $progid = $_POST['prograid'];
        $startYear = $_POST['progstartyear'];
        $progduration = $_POST['progduration'];
        $progoutput = $_POST['progoutput'];

        $query_rsIndicatorYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id='$startYear'");
        $query_rsIndicatorYear->execute();
        $row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
        $progstartingyear = $row_rsIndicatorYear['yr'];

        for ($i = 0; $i < $progduration; $i++) {
            $query_getProgTarget = $db->prepare("SELECT * FROM tbl_progdetails WHERE indicator ='$progoutput' AND year='$progstartingyear' AND progid='$progid'");
            $query_getProgTarget->execute();
            $row_rsProgTarget = $query_getProgTarget->fetch();
            $totalRows_ProgTarget = $query_getProgTarget->rowCount();

            if ($totalRows_ProgTarget > 0) {
                $query_usedTarget = $db->prepare("SELECT SUM(target) as projtarget FROM tbl_project_output_details WHERE progid='$progid' AND indicator ='$progoutput' and year='$progstartingyear' LIMIT 1");
                $query_usedTarget->execute();
                $rowproj = $query_usedTarget->fetch();
                $totalRows_usedTarget = $query_usedTarget->rowCount();

                if ($totalRows_usedTarget > 0) {
                    $totalUsedTarget  =  $rowproj['projtarget'];
                    $progtarget  =  $row_rsProgTarget['target'];
                    $projTarget = $progtarget - $totalUsedTarget;
                } else {
                    $projTarget  = $progtarget;
                }

                if ($projTarget > 0) {
                    //get financial years 
                    $query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE yr='$progstartingyear'");
                    $query_rsYear->execute();
                    $row_rsYear = $query_rsYear->fetch();
                    $totalRows_rsYear = $query_rsYear->rowCount();

                    $input .= '<option value="' . $row_rsYear['id'] . '">' . $row_rsYear['year'] . ' </option>';
                }
            } else {
                // echo $projstartingyear . ": No Program details Found";
            }
            $progstartingyear++;
        }

        echo $input;
    }

    if (isset($_POST['getYear'])) {
        $getYear = $_POST['getYear'];
        $query_rsIndicatorYear =  $db->prepare("SELECT yr FROM tbl_fiscal_year WHERE id='$getYear'");
        $query_rsIndicatorYear->execute();
        $row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
        $outputstartYear = $row_rsIndicatorYear['yr'];
        echo json_encode($outputstartYear);
    }

    if (isset($_POST['addoutput'])) {
        $progid = $_POST['myprogid'];
        $output = $_POST['outputids'];
        $outputfscyear = $_POST['outputfscyear'];
        $outputduration = $_POST['outputduration'];
        $outputIndicator = $_POST['indicatorid'];
        $outputbudget = $_POST['outputbudget'];
        $outputTarget = $_POST['outputTarget'];
        $unique_key = $_POST['key_unique'];

        $datecreated = date("Y-m-d");
        $createdby = $_POST['user_name'];
        $unit_type = isset($_POST['unit_type']) ?  $_POST['unit_type'] : 0;

        $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  unique_key='$unique_key'  and indicator='$outputIndicator'");
        $query_rsOutput->execute();
        $row_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        // var_dump($row_rsOutput);

        // if ($totalRows_rsOutput == 0) {
        $last_id = 0;
        if (isset($_POST['projid']) && !empty($_POST['projid'])) {
            $projid = $_POST['projid'];
            $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_details`(unique_key, progid, projid, outputid, indicator, year, duration, budget, total_target,unit_type) VALUES(:unique_key,:progid, :projid, :output, :indicator, :outputfscyear,  :outputduration, :budget,:outputTarget,:unit_type)");
            $result1  = $insertSQL1->execute(array(":unique_key" => $unique_key, ":progid" => $progid, ":projid" => $projid, ":output" => $output, ":indicator" => $outputIndicator, ":outputfscyear" => $outputfscyear, ":outputduration" => $outputduration, ":budget" => $outputbudget, ":outputTarget" => $outputTarget, ":unit_type"=>$unit_type));

            $last_id = $db->lastInsertId();
            $diss_states_target = $_POST['diss_states_target'];
            $state = $_POST['diss_states'];
            for ($i = 0; $i < count($state); $i++) {
                $insertSQL1 = $db->prepare("INSERT INTO `tbl_output_disaggregation`(projid, outputid, outputstate, total_target) VALUES(:projid, :output, :outputstate, :diss_states_target)");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":output" => $last_id, ":outputstate" => $state[$i], ":diss_states_target" => $diss_states_target[$i]));
            }
        } else {
            $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_details`(unique_key,progid, outputid, indicator, year, duration, budget, total_target,unit_type) VALUES(:unique_key, :progid, :output, :indicator, :outputfscyear,:outputduration, :budget,:outputTarget,:unit_type)");
            $result1  = $insertSQL1->execute(array(":unique_key" => $unique_key, ":progid" => $progid, ":output" => $output, ":indicator" => $outputIndicator, ":outputfscyear" => $outputfscyear, ":outputduration" => $outputduration, ":budget" => $outputbudget, ":outputTarget" => $outputTarget, ":unit_type"=> $unit_type));
            $last_id = $db->lastInsertId();
            if (isset($_POST['diss_states_target'])) {
                $diss_states_target = $_POST['diss_states_target'];
                //$outputlocations = $_POST['diss_states_locations'];
                $state = $_POST['diss_states'];
                for ($i = 0; $i < count($state); $i++) {
                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_output_disaggregation`(outputid, outputstate, total_target) VALUES(:output, :outputstate, :diss_states_target)");
                    $result1  = $insertSQL1->execute(array(":output" => $last_id, ":outputstate" => $state[$i], ":diss_states_target" => $diss_states_target[$i]));
                }
            }
        }
        echo json_encode(array("success" => true, "id" => $last_id));
        // } else {
        //     $last_id = 0;
        //     echo json_encode(array("success" => false, "id" => $last_id));
        // }
    }

    if (isset($_POST['editoutput'])) {
        $progid = $_POST['myprogid'];
        $output = $_POST['outputids'];
        $opid = $_POST['opid'];
        $projid = null;

        if (isset($_POST['projid'])) {
            $projid = $_POST['projid'];
        }

        $outputfscyear = $_POST['outputfscyear'];
        $outputduration = $_POST['outputduration'];
        $outputIndicator = $_POST['indicatorid'];
        $outputTarget = $_POST['outputTarget'];
        $datecreated = date("Y-m-d");
        $createdby = $_POST['user_name'];
        $outputbudget = $_POST['outputbudget'];
        $unique_key = $_POST['key_unique'];

        $insertSQL1 = $db->prepare("UPDATE  tbl_project_details SET unique_key=:unique_key, year=:outputfscyear, duration=:outputduration, budget=:budget, total_target=:outputTarget WHERE id =:opid");
        $result1  = $insertSQL1->execute(array(":unique_key" => $unique_key, ":outputfscyear" => $outputfscyear, ":outputduration" => $outputduration, ":budget" => $outputbudget, ":outputTarget" => $outputTarget, ":opid" => $opid));

        $deleteQuery = $db->prepare("DELETE FROM `tbl_output_disaggregation` WHERE outputid=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $opid));

        if ($results) {
            if (isset($_POST['diss_states_target'])) {
                $diss_states_target = $_POST['diss_states_target'];
                $state = $_POST['diss_states'];
                for ($i = 0; $i < count($state); $i++) {
                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_output_disaggregation`(projid, outputid, outputstate, total_target) VALUES(:projid, :output, :outputstate, :diss_states_target)");
                    $result1  = $insertSQL1->execute(array(":projid" => $projid, ":output" => $opid, ":outputstate" => $state[$i],   ":diss_states_target" => $diss_states_target[$i]));
                }
            }
        }
        echo json_encode(array("success" => true, "id" => $opid));
        // echo json_encode($opid);
    }

    if (isset($_POST["deleteItem"])) {
        $itemid = $_POST['itemId'];
        $deleteQuery = $db->prepare("DELETE FROM `tbl_project_details` WHERE id=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid));

        $deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projoutputid=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid));

        $deleteQuery = $db->prepare("DELETE FROM `tbl_output_disaggregation` WHERE outputid=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid));

        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Deleted";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while deletng the record!!";
        }
        echo json_encode($valid);
    }

    if (isset($_POST["deleteItems"])) {
        $itemids = $_POST['itemIds'];
        for ($i = 0; $i < count($itemids); $i++) {
            $deleteQuery = $db->prepare("DELETE FROM `tbl_project_details` WHERE id=:itemid");
            $results = $deleteQuery->execute(array(':itemid' => $itemids[$i]));

            $deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projoutputid=:itemid");
            $results = $deleteQuery->execute(array(':itemid' => $itemids[$i]));

            $deleteQuery = $db->prepare("DELETE FROM `tbl_output_disaggregation` WHERE outputid=:itemid");
            $results = $deleteQuery->execute(array(':itemid' => $itemids[$i]));
        }

        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Deleted";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while deletng the record!!";
        }

        echo json_encode($valid);
    }

    // edit functionality 
    if (isset($_POST['getprojectOutputData'])) {
        $outputids  = $_POST['outputids'];
        $getlocation  = $_POST['get_states'];
        $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  id='$outputids' ");
        $query_rsOutput->execute();
        $row_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        $opid = $row_rsOutput['id'];
        $projid = $row_rsOutput['projid'];
        $oipid = $row_rsOutput['outputid']; //outputid from program table 
        $indicatorid = $row_rsOutput['indicator'];
        $outputYear = $row_rsOutput['year'];
        $total_target = $row_rsOutput['total_target'];

        $outputDuration = $row_rsOutput['duration'];
        $outputBudget = $row_rsOutput['budget'];
        $programid = $row_rsOutput['progid'];

        $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id='$outputYear'");
        $query_rsYear->execute();
        $row_rsYear = $query_rsYear->fetch();
        $fscyear = $row_rsYear['yr'];

        // program target
        $query_rsprogBudget = $db->prepare("SELECT sum(budget) as budget, sum(target) as target FROM tbl_progdetails WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$fscyear'");
        $query_rsprogBudget->execute();
        $row_rsprogBudget = $query_rsprogBudget->fetch();
        $totalRows_rsprogBudget = $query_rsprogBudget->rowCount();
        $amountprogBudget = $totalRows_rsprogBudget > 0 ? $row_rsprogBudget['budget'] : 0;
        $progtarget = $totalRows_rsprogBudget > 0 ?  $row_rsprogBudget['target'] : 0;

        // sum of used target by projects(outputs)
        $query_rsprojBudget = $db->prepare("SELECT SUM(budget) as budget, SUM(total_target) as ptarget FROM tbl_project_details WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$outputYear' and projid IS NOT NULL");
        $query_rsprojBudget->execute();
        $row_rsprojBudget = $query_rsprojBudget->fetch();
        $totalRows_rsprojBudget = $query_rsprojBudget->rowCount();
        $amountprojBudget = $row_rsprojBudget['budget'];
        $projtotal_target = $row_rsprojBudget['ptarget'];

        $remaining_target = $progtarget - $projtotal_target;
        $ceil_target = $remaining_target + $total_target;

        $actual_budget = $amountprogBudget - $amountprojBudget;
        $remainingBudget =  ($amountprogBudget - $amountprojBudget) + $outputBudget;

        $query_rsStates =  $db->prepare("SELECT * FROM  tbl_output_disaggregation WHERE  outputid='$outputids' ");
        $query_rsStates->execute();
        $row_rsStates = $query_rsStates->fetch();
        $totalRows_rsStates = $query_rsStates->rowCount();

        $state_locations = '';
        if ($totalRows_rsStates > 0) {
            $row_count = 0;
            do {
                $row_count++;
                $state_locations .= '
                <tr></tr>
                <tr id="dissrow'  . $row_count  . '">
                    <td>
                    ' . $row_count . '
                    </td>
                    <td>
                        <select  data-id="' . $row_count . '" name="diss_states[]" id="diss_statesrow'  . $row_count . '" class="form-control  selected_diss" required="required">
                            <option value="">Select from list</option>';
                $getlocation =  $_POST['get_states'];
                $options = '';
                for ($j = 0; $j < count($getlocation); $j++) {
                    $query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE id='$getlocation[$j]'");
                    $query_loca->execute();
                    while ($row = $query_loca->fetch()) {
                        if ($row_rsStates['outputstate'] == $row['id']) {
                            $options .= '<option value="' . $row['id'] . '" selected> ' . $row['state'] . '</option>';
                        } else {
                            $options .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
                        }
                    }
                }
                $state_locations .= $options;
                $state_locations .= '
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="diss" value="diss">
                        <input type="number" name="diss_states_target[]" step="any" value="' . $row_rsStates['total_target'] . '"  id="diss_states_targetrow' . $row_count . '"  onkeyup="opTargetBal(' . $row_count . ')" onchange="opTargetBal(' . $row_count . ')"   placeholder="Enter"  class="form-control sub_total" style="width:85%; float:right" required/>
                    </td>  
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_diss("dissrow' . $row_count . '")>
                        <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </td>
                </tr>';
            } while ($row_rsStates = $query_rsStates->fetch());
        }

        $data  = array(
            "year" => $outputYear, "duration" => $outputDuration, "outputBudget" => $outputBudget,
            "opid" => $opid, "outputDuration" => $outputDuration, "actual_budget" => $actual_budget,
            "remainingBudget" => $remainingBudget,
            "total_target" => $total_target, "states" => $state_locations, "opfscyear" => $fscyear, "ceil_target" => $ceil_target, "remaining_target" => $remaining_target
        );
        echo json_encode($data);
    }

    // edit functionality 
    if (isset($_POST['get_ind_state_val'])) {
        include_once("../../system-labels.php");
        $outputids  = $_POST['get_ind_state_val'];
        $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  id='$outputids' ");
        $query_rsOutput->execute();
        $row_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();


        $opid = $row_rsOutput['id'];
        $oipid = $row_rsOutput['outputid']; //outputid from program table 
        $indicatorid = $row_rsOutput['indicator'];
        $outputYear = $row_rsOutput['year'];
        $total_target = $row_rsOutput['total_target'];

        $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorid' ");
        $query_Indicator->execute();
        $row = $query_Indicator->fetch();
        $indname = $row['indicator_name'];
        $indicator_unit = $row['indicator_unit'];

        $query_opunit = $db->prepare("SELECT unit FROM  tbl_measurement_units  WHERE id ='$indicator_unit'");
        $query_opunit->execute();
        $row = $query_opunit->fetch();
        $opunit = $row['unit'];

        $query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$oipid' ");
        $query_out->execute();
        $row_out = $query_out->fetch();
        $outputName = $row_out['output'];

        $state_locations = '';
        $query_rsStates =  $db->prepare("SELECT * FROM  tbl_output_disaggregation WHERE  outputid='$outputids'");
        $query_rsStates->execute();
        $row_rsStates = $query_rsStates->fetch();
        $totalRows_rsStates = $query_rsStates->rowCount();


        if ($totalRows_rsStates > 0) {
            $row = 0;
            do {
                $stateid = $row_rsStates['outputstate'];
                $query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE id='$stateid'");
                $query_loca->execute();
                $row_data = $query_loca->fetch();
                $state = $row_data['state'];

                $row++;
                $state_locations .= '
                <tr></tr>
                <tr id="indrow' . $row  . '">
                    <td>
                    ' . $row . '
                    </td>
                    <td>  
                         ' . $state . '
                    </td>
                    <td>
                    ' . $row_rsStates['total_target'] . ' ' . $opunit . '
                    </td>  
                </tr>';
            } while ($row_rsStates = $query_rsStates->fetch());
        }


        $data =  '<div class="row clearfix " id="Targetrowcontainer">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
                        <h5 style="color:#2B982B"><strong> Output: ' . $outputName . '</strong></h5> 
                        </div>
                    <div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
                        <h5 style="color:#2B982B"><strong> Indicator: ' . $opunit . " of " . $indname . ' </strong></h5> 
                    </div> 
                </div>
                <div class="body">
                    <div class="row clearfix "> 
                        <div class="col-md-12 ">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                                    <thead> 
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="70%">' . $level3label . '</th>
                                        <th width="25%">Output Share</th> 
                                    </tr>
                                    </thead>
                                    <tbody>
                                        ' . $state_locations . '
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>';
        echo $data;
    }

    if (isset($_POST['opdiss'])) {
        $outputids = $_POST['opdiss'];
        $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  id='$outputids' ");
        $query_rsOutput->execute();
        $row_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        $indicator = $row_rsOutput['indicator'];
        $query_rsIndicator = $db->prepare("SELECT indicator_name, indid,indicator_disaggregation, indicator_unit FROM tbl_indicator WHERE indid ='$indicator'");
        $query_rsIndicator->execute();
        $row_rsIndicator = $query_rsIndicator->fetch();
        $indname = $row_rsIndicator['indicator_name'];
        $opunit = $row_rsIndicator['indicator_unit'];
        $disaggregation = $row_rsIndicator['indicator_disaggregation'];

        $query_Unit = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id ='$opunit' ");
        $query_Unit->execute();
        $row = $query_Unit->fetch();
        $unit = $row['unit'];

        $output = $row_rsOutput['outputid'];
        $query_rsProgOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$output'");
        $query_rsProgOutput->execute();
        $row_rsProgOutput = $query_rsProgOutput->fetch();
        $outputName = $row_rsProgOutput['output'];

        $query_rsStates =  $db->prepare("SELECT * FROM  tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE  outputid='$outputids'");
        $query_rsStates->execute();
        $row_rsStates = $query_rsStates->fetch();
        $totalRows_rsStates = $query_rsStates->rowCount();

        $Targets  = '
		<div class="row clearfix" id="disstarget">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
				<div class="card">
					<div class="header">
						<div class="col-md-6" style="margin-top:5px; margin-bottom:5px">
							<h5 style="color:#2196F3"><strong> Output: ' . $outputName . '</strong></h5>
						</div>
						<div class="col-md-6" style="margin-top:5px; margin-bottom:5px">
							<h5 style="color:#2196F3"><strong> Indicator: ' . $unit . " of " . $indname . '</strong></h5>
							<input type="hidden" value="' . $indname . '" id="indicatorName' . trim($outputids) . '">
							<input type="hidden" value="' . $unit . '" id="unitNameL' . trim($outputids) . '">                                    
						</div> 
					</div>
					<div class="body"> 
						<div class="row clearfix" >
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
										<thead> 
											<tr>
												<th>#</th>
												<th>Location</th> 
												<th>Value</th> 
											</tr>
										</thead>
										<tbody>';
        $rowno = 0;
        do {
            $rowno++;
            $state =   $row_rsStates['outputstate'];
            $total_target =   $row_rsStates['total_target'];
            $level3 = $row_rsStates['state'];



            if ($disaggregation == 1) {

                $Targets .= '
                                                <tr>
                                                    <th>' . $rowno . '</th>
                                                    <th colspan="2">
                                                        <input type="hidden"   name="locate_output_name[]" id="locate_opid' . $outputids . '" value="' . $outputName . '"/>  
                                                        <input type="hidden"   name="level3label' . $state . $outputids . '[]" id="level3label' . $state . $outputids . '" value="' . $level3 . '"/>
                                                        <input type="hidden"   name="unitName' . $state . $outputids . '[]" id="unitName' . $state . $outputids . '" value="' . $unit . '"/>  
                                                        <input type="hidden" data-id="' . $level3 . '"  name="outputstate' . $outputids . '[]" class="outputstate' . $outputids . '" value="' . $state . '" />
                                                        ' . $level3label . ': ' . $level3 . '
                                                        <input type="hidden"  id="ceilinglocation_target' . $state . $outputids . '"  name="ceiloutputlocationtarget' . $outputids . '[]" value="' . $total_target . '" />
                                                        <span id="state_ceil' . $state . $outputids . '" style="color:red" > (' . number_format($total_target, 2) . ' ' . $unit . ')</span> 
                                                    </th>
                                                </tr>';

                $query_ward = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE level3=:level3 and indicatorid=:indid");
                $query_ward->execute(array(":level3" => $state, ":indid" => $indicator));
                $row_ward = $query_ward->fetch();
                $locations = $query_ward->rowCount();
                $p = 0;
                do {
                    $p++;
                    $location = $row_ward['disaggregations'];
                    $locationid = $row_ward['id'];
                    $gen_number =  mt_rand(15, 500);
                    $number = $p . $gen_number;

                    $Targets  .= '
                                                    <tr>
                                                    <td>' . $rowno . "." . $p . '</td>
                                                    <td>' . $location . '</td>
                                                        <td>
                                                            <input type="hidden"   name="outputlocation' . $state . $outputids . '[]" id="locate' . $number . '" value="' . $locationid . '"/> 
                                                            <input type="number" data-loc="' . $location . '"  data-id="' . $outputids . '" id="locate_numb' . $number . '" placeholder="' . $unit . '" class="form-control locate_total' . $state .  $outputids . '" onkeyup=get_sum("' . $state . '","' . $number . '") onchange=get_sum("' . $state . '","' . $number . '") name="outputlocationtarget' . $state . $outputids . '[]" value="" required />
                                                        </td>
                                                    </tr>';
                } while ($row_ward = $query_ward->fetch());
            } else {
                $Targets .= '
                                                <tr>
                                                    <td>' . $rowno . '</td>
                                                    <td>
                                                        ' . $level3 . '
                                                    </td>
                                                    <td>
                                                        <span style="color:red" > 
                                                            ' . number_format($total_target, 2) . ' ' . $unit . '
                                                        </span> 
                                                    </td>
                                                </tr>';
            }
        } while ($row_rsStates = $query_rsStates->fetch());
        $Targets  .= '
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
        echo $Targets;
    }

    if (isset($_POST['get_target_div'])) {
        $programid = $_POST['prograid'];
        $outputIds = $_POST['outputid'];
        $query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE id = '$outputIds' ");
        $query_OutputData->execute();
        $rows_OutpuData = $query_OutputData->rowCount();
        $row_OutputData =  $query_OutputData->fetch();

        if ($rows_OutpuData > 0) {
            $op_target = $row_OutputData['total_target'];
            $op_budget = $row_OutputData['budget'];
            $indicator = $row_OutputData['indicator'];
            $query_rsIndicator = $db->prepare("SELECT indicator_name, indid, indicator_disaggregation FROM tbl_indicator WHERE indid ='$indicator'");
            $query_rsIndicator->execute();
            $row_rsIndicator = $query_rsIndicator->fetch();
            $indname = $row_rsIndicator['indicator_name'];
            $ben_diss = $row_rsIndicator['indicator_disaggregation'];

            $ben_diss = ($ben_diss  != 1)  ? 0 : $ben_diss;


            $query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit AS unit FROM tbl_indicator INNER JOIN tbl_measurement_units ON tbl_measurement_units.id = tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicator'");
            $query_Indicator->execute();
            $row = $query_Indicator->fetch();
            $unit = $row['unit'];

            $year = $row_OutputData['year'];
            $query_rsIndicatorYear =  $db->prepare("SELECT yr FROM tbl_fiscal_year WHERE id='$year'");
            $query_rsIndicatorYear->execute();
            $row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
            $projstartyear = $row_rsIndicatorYear['yr'];

            $output = $row_OutputData['outputid'];
            $query_rsOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$output'");
            $query_rsOutput->execute();
            $row_rsOutput = $query_rsOutput->fetch();
            $outputName = $row_rsOutput['output'];

            $projoutputDValue = $row_OutputData['duration'];
            $s_date = $projstartyear . "-07-01";
            $project_end_date = date('Y-m-d', strtotime($s_date . ' + ' . $projoutputDValue . ' days'));
            $end_month = date('m', strtotime($project_end_date));
            $end_year_c = date('Y', strtotime($project_end_date));
            $end_year = $end_month >= 7 && $end_month <= 12 ? $end_year_c : $end_year_c - 1;

            $years = ($end_year - $projstartyear) + 1;

            // $years = floor($projoutputDValue / 365);
            // $remainder = $projoutputDValue % 365; 
            // $years = ($remainder > 0) ?  $years + 1 : 0 ;

            $spanYear = $TargetPlan = $containerTH = $containerTB = $containerTHYears =  $containerYears = '';
            for ($i = 0; $i < $years; $i++) {
                $endyear = $projstartyear + 1;
                $query_rsprogTarget =  $db->prepare("SELECT target, budget FROM tbl_progdetails WHERE indicator ='$indicator' and progid ='$programid' and year ='$projstartyear'");
                $query_rsprogTarget->execute();
                $row_rsprogTarget = $query_rsprogTarget->fetch();
                $totalRows_rsprogTarget = $query_rsprogTarget->rowCount();
                $progTarget = $totalRows_rsprogTarget > 0 ? $row_rsprogTarget['target'] : 0;
                $program_budget = $totalRows_rsprogTarget > 0 ? $row_rsprogTarget['budget'] : 0;

                $query_rsprojTarget =  $db->prepare("SELECT SUM(target) as target,  SUM(budget) as budget FROM tbl_project_output_details WHERE indicator ='$indicator' and progid ='$programid' and year ='$projstartyear'");
                $query_rsprojTarget->execute();
                $row_rsprojTarget = $query_rsprojTarget->fetch();
                $totalRows_rsprojTarget = $query_rsprojTarget->rowCount();
                $projTarget = $totalRows_rsprojTarget > 0 ?  $row_rsprojTarget['target'] : 0;
                $project_budget = $totalRows_rsprojTarget > 0 ?  $row_rsprojTarget['budget'] : 0;
                $remaining = $progTarget - $projTarget;
                $remaining_budget = $program_budget - $project_budget;
                $arr =  ($remaining > 0) ? $remaining : 0;
                $budget_remaining =  ($remaining_budget > 0) ? $remaining_budget : 0;
                // targets
                $containerTH .=
                    '<th>' . $projstartyear  . '/' . $endyear . '
                    <input type="hidden" class="output_years' . $outputIds  . '" name="output_years' . $outputIds  . '[]" value="' . $projstartyear . ' " >
                    <input type="hidden" name="dboutputId[]" value="' . $outputName . ' " >  
                    <input type="hidden" id="outputName' . $outputIds . '" name="outputName[]" value="' . $outputIds . ' " > 
                    <input type="hidden"   id="cyear_target' . $outputIds .  $projstartyear . '" name="cyear_target' . $outputIds . '[]" value="' . $arr . ' " >
                    <span>Program Target Bal: </span><span style="color:red" id="year_target' . $outputIds .  $projstartyear . '" > ' . number_format($arr, 2) . '</span>
                </th>';
                $containerTB .= '<td> 
                <input type="number" data-id=""  min="0"   name="target_year' . $outputIds . '[]" placeholder="target"  id="target_year' . $outputIds . $projstartyear . '" class="form-control workplanTarget' . $outputIds . '" min="0" onkeyup=get_op_sum_target(' . $outputIds . ',' . $projstartyear . ') required >
                </td>';

                // budgets
                $containerTHYears .= '<th>' . $projstartyear  . '/' . $endyear . '
                <input type="hidden" name="b_dboutputId[]" value="' . $outputName . ' " >  
                <input type="hidden" id="b_outputName' . $outputIds . '" name="outputName[]" value="' . $outputIds . ' " > 
                <input type="hidden"   id="b_cyear_budget' . $outputIds .  $projstartyear . '" name="cyear_budget' . $outputIds . '[]" value="' . $budget_remaining . ' " >
                  <span>Program Budget Bal: </span><span style="color:red" id="year_budget' . $outputIds .  $projstartyear . '" > ' . number_format($budget_remaining, 2) . '</span>
                </th>';

                $containerYears .= '<td> 
                <input type="number" data-id="" min="0"  name="budget_year' . $outputIds . '[]" placeholder="Budget"  id="budget_year' . $outputIds . $projstartyear . '" class="form-control output_budget' . $outputIds . '" min="0" onkeyup=get_op_sum_budget(' . $outputIds . ',' . $projstartyear . ') required >
                </td>';

                $projstartyear++;
            }

            $data = '
            <fieldset class="scheduler-border row setup-content" style="padding:10px">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output: ' . $outputName . ' </legend>
                <div class="row clearfix " id="Targetrowcontainer">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <div class="col-md-5 clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <h5 style="color:#2B982B"><strong> Output: ' . $outputName . '</strong></h5>
                                    <input type="hidden" value="' . $outputName . '" id="workplan_opName' . trim($outputIds) . '">
                                    </div>
                                <div class="col-md-5 clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <h5 style="color:#2B982B"><strong> Indicator: ' . $unit . ' of ' . $indname . '</strong></h5>
                                    <input type="hidden" value="' . $indname . '" id="indicatorName' . trim($outputIds) . '">
                                    </div>
                                <div class="col-md-2 clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <h5 style="color:#2B982B"><strong> Unit : ' . $unit . '</strong></h5>
                                    <input type="hidden" value="' . $unit . '" id="unit' . trim($outputIds) . '">
                                    <input type="hidden" value="' . $ben_diss . '" id="ben_diss_value' . trim($outputIds) . '">
                                </div>  
                            </div>
                            <div class="body">
                                <div class="row clearfix "> 
                                    <div class="col-md-12 ">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                                                <thead> 
                                                    <tr>
                                                        <th colspan="' . $years . '" >
                                                        <input type="hidden"   id="opid_name' . $outputIds . '" name="opid_name' . $outputIds . '[]" value="' . $outputName . ' " >
                                                        <input type="hidden"   id="coptarget_target' . $outputIds . '" name="coptarget_target' . $outputIds . '[]" value="' . $op_target . ' " >
                                                            <span>Output Target Bal: </span>
                                                            <span style="color:red" id="op_target' . $outputIds . '" >
                                                                ' . number_format($op_target, 2) . '
                                                            </span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        ' . $containerTH . '
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr> ' .  $containerTB . ' </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                            <div class="row clearfix "> 
                                <div class="col-md-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="budgets" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="' . $years . '" >
                                                    <input type="hidden"   id="y_opid_name' . $outputIds . '" name="opid_name' . $outputIds . '[]" value="' . $outputName . ' " >
                                                    <input type="hidden"   id="y_copbudget_budget' . $outputIds . '" name="copbudget_budget' . $outputIds . '[]" value="' . $op_budget . ' " >
                                                        <span>Output Budget Bal: </span>
                                                        <span style="color:red" id="y_op_budget' . $outputIds . '" >
                                                            ' . number_format($op_budget, 2) . '
                                                        </span>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    ' . $containerTHYears . '
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr> ' .  $containerYears . ' </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </fieldset>';
            echo $data;
        }
    }

    if (isset($_POST['getremainingTarget'])) {
        $projoutput = $_POST['projoutputid'];
        $projstartingyear = $_POST['projstartingyear'];
        $progid = $_POST['prograid'];

        $query_getProgTarget = $db->prepare("SELECT * FROM tbl_progdetails WHERE indicator ='$projoutput' AND year='$projstartingyear' AND progid='$progid'");
        $query_getProgTarget->execute();
        $row_rsProgTarget = $query_getProgTarget->fetch();
        $totalRows_ProgTarget = $query_getProgTarget->rowCount();
        $progtarget  =  $row_rsProgTarget['target'];

        if ($totalRows_ProgTarget > 0) {
            $query_projcost = $db->prepare("SELECT SUM(target) as projtarget FROM tbl_project_output_details WHERE progid='$progid' AND indicator ='$projoutput' and year='$projstartingyear' LIMIT 1");
            $query_projcost->execute();
            $rowproj = $query_projcost->fetch();
            $totalRows_projcost = $query_projcost->rowCount();

            if ($totalRows_projcost > 0) {
                $totalUsedTarget  =  $rowproj['projtarget'];
                $projTarget = $progtarget - $totalUsedTarget;

                if ($projTarget > 0) {
                    $data = array("Msg" => "success", "projTarget" => $projTarget, "targetYear" => $projstartingyear);
                    echo json_encode($data);
                } else {
                    $data = array("Msg" => "error");
                    echo json_encode($data);
                }
            } else {
                $data = array("Msg" => "error");
                echo json_encode($data);
            }
        } else {
            $data = array("errorMsg" => "error");
            echo json_encode($data);
        }
    }

    if (isset($_POST['getImplementingPartner'])) {
        $leadImplementor = $_POST['leadImplementor'];
        $query_rsPartner =  $db->prepare("SELECT * FROM tbl_financiers WHERE active=1 ");
        $query_rsPartner->execute();
        echo '
        <option value="">...Select from list...</option>
        <option value="0">Not Applicable</option>  
        ';
        while ($row_rsPartner = $query_rsPartner->fetch()) {
            if ($row_rsPartner['id']  != $leadImplementor) {
                echo '<option value="' . $row_rsPartner['id'] . '"> ' . $row_rsPartner['financier'] . '</option>';
            }
        }
    }

    if (isset($_POST['getcollaborativepratner'])) {
        $leadImplementor = $_POST['leadImpl'];
        $implPa = $_POST['implPa'];
        $query_rsPartner =  $db->prepare("SELECT * FROM tbl_partners");
        $query_rsPartner->execute();

        echo '<option value="">...Select from list...</option>
                <option value="0">Not Applicable</option>';
        while ($row_rsPartner = $query_rsPartner->fetch()) {
            $ptnid = $row_rsPartner['ptnid'];
            $handler = true;
            if (in_array($ptnid, $implPa)) {
                $handler = true;
            } else {
                $handler = false;
            }
            if ($ptnid != $leadImplementor &&  $handler == false) {
                echo '<option value="' . $ptnid . '"> ' . $row_rsPartner['partnername'] . '</option>';
            }
        }
    }

    if (isset($_POST['getoutputDetailsDisp'])) {
        $outputIds = $_POST['outputDispId'];
        $counter = 0;
        $Targets = '';

        for ($p = 0; $p < count($outputIds); $p++) {
            //get project output details  
            $counter++;
            $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE id ='$outputIds[$p]'");
            $query_rsOutput->execute();
            $row_rsOutput = $query_rsOutput->fetch();

            $opid = $row_rsOutput['id'];
            $oipid = $row_rsOutput['outputid'];
            $indicatorID = $row_rsOutput['indicator'];
            $outcomeYear = $row_rsOutput['year'];
            $outcomeDuration = $row_rsOutput['duration'];
            $outputBudget = $row_rsOutput['budget'];
            $total_target = $row_rsOutput['total_target'];

            $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorID' ");
            $query_Indicator->execute();
            $row = $query_Indicator->fetch();
            $indname = $row['indicator_name'];
            $unit = $row['indicator_unit'];

            $query_opunit = $db->prepare("SELECT unit FROM  tbl_measurement_units  WHERE id ='$unit'");
            $query_opunit->execute();
            $row = $query_opunit->fetch();
            $opunit = $row['unit'];

            $query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$oipid' ");
            $query_out->execute();
            $row_out = $query_out->fetch();
            $outputName = $row_out['output'];

            $query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE  id='$outcomeYear'");
            $query_rsYear->execute();
            $row_rsYear = $query_rsYear->fetch();
            $fscyear = $row_rsYear['year'];

            $Targets .= '
            <tr>
                <td>' . $counter . '  </td> 
                <td>' . $outputName . '  </td> 
                <td>' .  $opunit . ' of ' . $indname . '  </td>  
                <td>' . $fscyear . '  </td> 
                <td>' . number_format($outcomeDuration) . ' Days</td> 
                <td>' . number_format($outputBudget, 2) . '  </td>   
                <td>' . number_format($total_target, 2) . '  </td>  
            </tr> 
            ';
        }

        $type = '
            <div class="row clearfix " id="">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix " id="">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 
                                    <div class="table-responsive">
                                        <table summary="" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Output Name </th>
                                                    <th>Indicator </th> 
                                                    <th>Start Year</th>
                                                    <th>Duration </th>
                                                    <th>Budget (Ksh)</th>
                                                    <th>Total Output</th>  
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ' . $Targets . '
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        echo $type;
    }

    if (isset($_POST['getprojcost'])) {
        $outputIds = $_POST['outputDispId'];
        $counter = 0;
        for ($i = 0; $i < count($outputIds); $i++) {
            $query_rsOutput =  $db->prepare("SELECT budget FROM  tbl_project_details WHERE id ='$outputIds[$i]'");
            $query_rsOutput->execute();
            $row_rsOutput = $query_rsOutput->fetch();
            $totalRows_rsOutput = $query_rsOutput->rowCount();
            $counter = $counter + $row_rsOutput['budget'];
        }
        echo json_encode($counter);
    }

    if (isset($_POST['getfinancier'])) {
        $progid = $_POST['getfinancier'];
        $query_rsFunding =  $db->prepare("SELECT p.amountfunding, p.sourcecategory,f.type  FROM tbl_myprogfunding p INNER JOIN tbl_funding_type f  ON  p.sourcecategory= f.id WHERE progid =:progid");
        $query_rsFunding->execute(array(":progid" => $progid));
        $row_rsFunding = $query_rsFunding->fetch();
        $totalRows_rsFunding = $query_rsFunding->rowCount();

        $options = '<option value="">Select Source list</option>';
        do {
            $source_category = $row_rsFunding['sourcecategory'];
            $source_name = $row_rsFunding['type'];
            $progfunds = $row_rsFunding['amountfunding'];

            $query_rsprojFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_projfunding 
            WHERE progid =:progid and sourcecategory =:source_category ");
            $query_rsprojFunding->execute(array(":progid" => $progid, ":source_category" => $source_category));
            $row_rsprojFunding = $query_rsprojFunding->fetch();
            $totalRows_rsprojFunding = $query_rsprojFunding->rowCount();

            $projfunds = $row_rsprojFunding['amountfunding'];
            $remaining = $progfunds - $projfunds;
            if ($remaining > 0) {
                $options .=  '<option value="' . $source_category . '">' . $source_name . '</option>';
            }
        } while ($row_rsFunding = $query_rsFunding->fetch());
        echo $options;
    }

    if (isset($_POST['finance'])) {
        $sourcecategory = $_POST['sourcecategory'];
        $progid = $_POST['progid'];

        //get  funding 
        $query_rsFunding =  $db->prepare("SELECT amountfunding FROM tbl_myprogfunding   WHERE sourcecategory =:sourcecategory AND progid=:progid");
        $query_rsFunding->execute(array(":sourcecategory" => $sourcecategory, ":progid" => $progid));
        $row_rsFunding = $query_rsFunding->fetch();
        $totalRows_rsFunding = $query_rsFunding->rowCount();
        $amountFunding = $row_rsFunding['amountfunding'];

        $query_rsprojFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_projfunding WHERE sourcecategory =:sourcecategory AND progid=:progid");
        $query_rsprojFunding->execute(array(":sourcecategory" => $sourcecategory, ":progid" => $progid));
        $row_rsprojFunding = $query_rsprojFunding->fetch();
        $totalRows_rsprojFunding = $query_rsprojFunding->rowCount();
        $amountprojFunding = $row_rsprojFunding['amountfunding'];
        $remaining =  $amountFunding - $amountprojFunding;
        $arr = [];

        if ($remaining > 0) {
            $arr =   array("remaining" => $remaining, "msg" => "true");
        } else {
            $arr =   array("msg" => "false");
        }
        echo json_encode($arr);
    }

    if (isset($_POST['getOCIndUnit'])) {
        $ocIndUnit = $_POST['ocIndUnit'];
        $query_rsUnit = $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE i.indid ='$ocIndUnit'");
        $query_rsUnit->execute();
        $row_rsUnit = $query_rsUnit->fetch();
        $rsUnit = "(" . $row_rsUnit['unit'] . ")";
        echo $rsUnit;
    }

    if (isset($_POST['get_state_diss'])) {
        $data = '';
        for ($i = 0; $i < count($states); $i++) {
            $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
            $query_rsLocations->execute(array(":id" => $projlga));
            $row_rsLocations = $query_rsLocations->fetch();
            $total_locations = $query_rsLocations->rowCount();
            if ($total_locations > 0) {
                $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
            }
        }
    }

    if (isset($_POST['validate_state'])) {
        $outputIds = $_POST['outputIds'];
        $projstate = $_POST['projstate'];

        $outputstate = [];
        for ($i = 0; $i < count($outputIds); $i++) {
            $query_rsStates =  $db->prepare("SELECT * FROM  tbl_output_disaggregation WHERE  outputid='$outputIds[$i]' ");
            $query_rsStates->execute();
            $row_rsStates = $query_rsStates->fetch();
            $totalRows_rsStates = $query_rsStates->rowCount();
            do {
                $outputstate[] = $row_rsStates['outputstate'];
            } while ($row_rsStates = $query_rsStates->fetch());
        }

        $outputstates = array_unique($outputstate);

        $data = [];
        for ($j = 0; $j < count($projstate); $j++) {
            if (in_array($projstate[$j], $outputstates)) {
                $data[] = true;
            } else {
                $data[] = false;
            }
        }

        if (in_array(false, $data)) {
            $valid['success'] = false;
            $valid['messages'] = "Error Some states still require updating !!";
        } else {
            $valid['success'] = true;
            $valid['messages'] = "Successfully added all states ";
        }
        echo json_encode($valid);
    }

    if (isset($_POST['projstateName'])) {
        $getward = explode(",", $_POST['projstateName']);
        $state = [];
        for ($i = 0; $i < count($getward); $i++) {
            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE  id='$getward[$i]' LIMIT 1");
            $query_rsComm->execute();
            $row_rsComm = $query_rsComm->fetch();
            $state[] = $row_rsComm['state'];
        }
        echo json_encode($state);
    }

    if (isset($_POST['get_comm'])) {
        $community = explode(",", $_POST['get_comm']);

        $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
        $query_rsComm->execute();
        $row_rsComm = $query_rsComm->fetch();
        $totalRows_rsComm = $query_rsComm->rowCount();
        $data = '';
        $id = [];
        do {
            $comm = $row_rsComm['id'];
            $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm ");
            $query_ward->execute(array(":comm" => $comm));
            while ($row = $query_ward->fetch()) {
                $projlga = $row['id'];
                $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                $query_rsLocations->execute(array(":id" => $projlga));
                $row_rsLocations = $query_rsLocations->fetch();
                $total_locations = $query_rsLocations->rowCount();
                if ($total_locations > 0) {
                    if (!in_array($comm, $id)) {
                        if (in_array($comm, $community)) {
                            $data .= '<option value="' . $row_rsComm['id'] . '" selected>' . $row_rsComm['state'] . '</option>';
                        } else {
                            $data .= '<option value="' . $row_rsComm['id'] . '">' . $row_rsComm['state'] . '</option>';
                        }
                    }
                    $id[] = $row_rsComm['id'];
                }
            }
        } while ($row_rsComm = $query_rsComm->fetch());
        echo $data;
    }

    if (isset($_POST['get_ward'])) {
        $level2 = explode(",", $_POST['get_ward']);
        $getward = $_POST['conservancy'];

        $data = '';
        for ($i = 0; $i < count($getward); $i++) {
            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id='$getward[$i]' LIMIT 1");
            $query_rsComm->execute();
            $row_rsComm = $query_rsComm->fetch();
            $community = $row_rsComm['state'];

            $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$getward[$i]'");
            $query_ward->execute();
            $data .= '
            <optgroup label="' . $community . '"> ';
            while ($row = $query_ward->fetch()) {
                $projlga = $row['id'];
                $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                $query_rsLocations->execute(array(":id" => $projlga));
                $row_rsLocations = $query_rsLocations->fetch();
                $total_locations = $query_rsLocations->rowCount();
                if ($total_locations > 0) {
                    if (in_array($row['id'], $level2)) {
                        $data .= '<option value="' . $row['id'] . '" selected> ' . $row['state'] . '</option>';
                    } else {
                        $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
                    }
                }
            }
            $data .= '
            <optgroup>';
        }
        echo $data;
    }
    if (isset($_POST['get_level3'])) {
        $getlocation = $_POST['level2'];
        $get_level3 = explode(",", $_POST['get_level3']);
        $data = '';
        for ($j = 0; $j < count($getlocation); $j++) {
            $query_ward = $db->prepare("SELECT id, state, parent FROM tbl_state WHERE id='$getlocation[$j]'");
            $query_ward->execute();
            $row_ward = $query_ward->fetch();
            $ward = $row_ward['state'];
            $parent = $row_ward['parent'];

            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id='$parent' LIMIT 1");
            $query_rsComm->execute();
            $row_rsComm = $query_rsComm->fetch();
            $community = $row_rsComm['state'];

            $query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$getlocation[$j]'");
            $query_loca->execute();
            $data .= '<optgroup label="' . $ward . ' (' .  $community . ')">';
            while ($row = $query_loca->fetch()) {
                if (in_array($row['id'], $get_level3)) {
                    $data .= '<option value="' . $row['id'] . '" selected> ' . $row['state'] . '</option>';
                } else {
                    $data .= '<option value="' . $row['id'] . '" > ' . $row['state'] . '</option>';
                }
            }
            $data .= '<optgroup>';
        }
        echo $data;
    }
    if (isset($_POST['get_target_bal'])) {
        $progid = $_POST['progid'];
        $indicatorid =  $_POST['indicatorid'];
        $opduration = $_POST['opduration'];
        $projfscyear = $_POST['projfscyear'];

        $total_progtarget = 0;
        $amountprojtarget = 0;

        for ($i = 0; $i < $opduration; $i++) {
            $query_Target = $db->prepare("SELECT * FROM `tbl_progdetails` WHERE progid ='$progid' and year ='$projfscyear' and indicator ='$indicatorid'");
            $query_Target->execute();
            $row_Target = $query_Target->fetch();
            $totalRows_Target = $query_Target->rowCount();
            $total_progtarget = $total_progtarget +  $row_Target['target'];

            $query_projTarget = $db->prepare("SELECT SUM(target) as projtarget  FROM tbl_project_output_details WHERE progid='$progid' AND indicator ='$indicatorid' and year='$projfscyear' ");
            $query_projTarget->execute();
            $rowproj = $query_projTarget->fetch();
            $totalRows_Target = $query_projTarget->rowCount();
            $amountprojtarget = $amountprojtarget + $rowproj['projtarget'];
            $projfscyear++;
        }

        $remaining_target = $total_progtarget - $amountprojtarget;

        if ($remaining_target > 0) {
            echo json_encode(array("success" => true, "remaining" => $remaining_target));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    if (isset($_GET['get_start_year'])) {
        $starting_year = $_GET['starting_year'];
        $projduration = $_GET['projduration'];
        $s_date = $starting_year . "-07-01";
        $project_end_date = date('Y-m-d', strtotime($s_date . ' + ' . $projduration . ' days'));
        $end_month = date('m', strtotime($project_end_date));
        $end_year_c = date('Y', strtotime($project_end_date));
        $endyear = $end_month >= 7 && $end_month <= 12 ? $end_year_c : $end_year_c - 1;


        echo json_encode(array("msg" => true, "endyear" => $endyear, "project_end_date" => $project_end_date));
    }
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    echo $ex->getMessage();
}
