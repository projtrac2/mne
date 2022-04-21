<?php
include_once "controller.php";
try {

    if (isset($_POST['projcode'])) {
        $data = $_POST['projcode'];
        $query_rsProject = $db->prepare("SELECT projcode FROM tbl_projects WHERE projcode='$data'");
        $result = $query_rsProject->execute();
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
            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id='$getward[$i]' LIMIT 1");
            $query_rsComm->execute();
            $row_rsComm = $query_rsComm->fetch();
            $community = $row_rsComm['state'];

            $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$getward[$i]'");
            $query_ward->execute();
            $data .= '
            <optgroup label="' . $community . '"> ';
            while ($row = $query_ward->fetch()) {
                $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
            }
            $data .= '
            <optgroup>';
        }
        echo $data;
    }

    if (isset($_POST['getlocation'])) {
        $getlocation = explode(",", $_POST['getlocation']);
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

        $query_Indicator = $db->prepare("SELECT indid, indname FROM tbl_progdetails INNER JOIN tbl_indicator ON tbl_indicator.indid = tbl_progdetails.indicator WHERE progid ='$progid' AND id='$indicator'");
        $query_Indicator->execute();
        $row = $query_Indicator->fetch();

        echo json_encode($row);
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
                $query_projTarget = $db->prepare("SELECT SUM(target) as projtarget FROM tbl_project_output_details WHERE progid='$progid' AND indicator ='$indicator' and year='$year' ");
                $query_projTarget->execute();
                $rowproj = $query_projTarget->fetch();
                $totalRows_Target = $query_projTarget->rowCount();

                $totalUsedTarget  =  $rowproj['projtarget'];
                $projTargetbal = $progTarget - $totalUsedTarget;
                if ($projTargetbal > 0) {
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

    if (isset($_POST['finance'])) {
        $financierid = $_POST['financeId'];
        //get  funding 
        $query_rsFunding =  $db->prepare("SELECT code, rate, amountfunding FROM tbl_myprogfunding f inner join tbl_currency c on c.id=f.currency WHERE f.id ='$financierid'");
        $query_rsFunding->execute();
        $row_rsFunding = $query_rsFunding->fetch();
        $totalRows_rsFunding = $query_rsFunding->rowCount();
        $rate = $row_rsFunding['rate'];
        $amountFunding = $row_rsFunding['amountfunding'] * $rate;

        $query_rsprojFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_myprojfunding WHERE progfundid ='$financierid'");
        $query_rsprojFunding->execute();
        $row_rsprojFunding = $query_rsprojFunding->fetch();
        $totalRows_rsprojFunding = $query_rsprojFunding->rowCount();
        $amountprojFunding = $row_rsprojFunding['amountfunding'];

        $remaining =  $amountFunding - $amountprojFunding;

        $arr = [];

        if ($remaining > 0) {
            $remaining = $row_rsFunding['code'] . ',' . $remaining;
            $arr =   array("remaining" => $remaining, "msg" => "true");
        } else {
            $arr =   array("msg" => "false");
        }
        echo json_encode($arr);
    }

    if (isset($_POST['getoutputBudget'])) {
        $indicatorid = $_POST['indicatorid'];
        $programid = $_POST['programid'];
        $outputYear = $_POST['outputYear'];

        $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id='$outputYear'");
        $query_rsYear->execute();
        $row_rsYear = $query_rsYear->fetch();
        $fscyear = $row_rsYear['yr'];

        $query_rsprogBudget = $db->prepare("SELECT budget FROM tbl_progdetails WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$fscyear'");
        $query_rsprogBudget->execute();
        $row_rsprogBudget = $query_rsprogBudget->fetch();
        $totalRows_rsprogBudget = $query_rsprogBudget->rowCount();
        $amountprogBudget = $row_rsprogBudget['budget'];

        $query_rsprojBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$outputYear'");
        $query_rsprojBudget->execute();
        $row_rsprojBudget = $query_rsprojBudget->fetch();
        $totalRows_rsprojBudget = $query_rsprojBudget->rowCount();
        $amountprojBudget = $row_rsprojBudget['budget'];
        $remaining =  $amountprogBudget - $amountprojBudget;

        $arr = [];
        if ($remaining > 0) {
            $arr =   array("remaining" => $remaining, "msg" => "true");
        } else {
            $arr =   array("msg" => "false");
        }

        echo json_encode($arr);
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
        $outputdataSource = implode(",", $_POST['outputdataSource']);
        $outputDataCollectionMethod = $_POST['outputDataCollectionMethod'];
        $outputMonitorigFreq = $_POST['outputMonitorigFreq'];
        // $outputresponsible = $_POST['outputresponsible'];
        $outputReportUser = implode(",", $_POST['outputReportUser']);
        $outputReportingTimeline = implode(",", $_POST['outputReportingTimeline']);
        $datecreated = date("Y-m-d");
        $createdby = $_POST['user_name'];
        //$createdby = 1;
        $outputbudget = $_POST['outputbudget'];

        if (isset($_POST['myprojid']) && !empty($_POST['myprojid'])) {
            $projid = $_POST['myprojid'];
            $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_details`(progid, projid, outputid, indicator, year, duration, budget) VALUES(:progid, :projid, :output, :indicator, :outputfscyear, :outputduration, :budget)");
            $result1  = $insertSQL1->execute(array(":progid" => $progid, ":projid" => $projid, ":output" => $output, ":indicator" => $outputIndicator, ":outputfscyear" => $outputfscyear, ":outputduration" => $outputduration, ":budget" => $outputbudget));
            $last_id = $db->lastInsertId();
            // echo $last_id;

            $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_outputs`(projid, outputid, indicator, data_source, data_collection_method, monitoring_frequency, report_user, reporting_timeline, date_created, created_by) VALUES(:projid, :output, :indicator, :data_source, :data_collection_method, :monitoring_frequency, :report_user, :reporting_timeline, :date_added,:added_by)");
            $result1  = $insertSQL1->execute(array(":projid" => $projid, ":output" => $last_id, ":indicator" => $outputIndicator, ":data_source" => $outputdataSource, ":data_collection_method" => $outputDataCollectionMethod, ":monitoring_frequency" => $outputMonitorigFreq, ":report_user" => $outputReportUser, ":reporting_timeline" => $outputReportingTimeline, ":date_added" => $datecreated, ":added_by" => $createdby));

            if (isset($_POST['outputrisk'])) {
                for ($i = 0; $i < count($_POST['outputrisk']); $i++) {
                    $riskid = $_POST['outputrisk'][$i];
                    $type = 3;
                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_output_risks`(projid, rskid, outputid, type) VALUES(:projid, :rskid, :outputid, :type )");
                    $result1  = $insertSQL1->execute(array(":projid" => $projid, ":rskid" => $riskid, ":outputid" => $last_id, ":type" => $type));
                }
            }

            $updateSQL = $db->prepare("UPDATE `tbl_project_output_details` SET projoutputid = :projoutputid WHERE projid=:projid");
            $updateSQL->execute(array(":projoutputid" => $last_id, ":projid" => $projid));
        } else {
            $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_details`(progid, outputid, indicator, year, duration, budget) VALUES(:progid, :output, :indicator,  :outputfscyear, :outputduration, :budget)");
            $result1  = $insertSQL1->execute(array(":progid" => $progid, ":output" => $output, ":indicator" => $outputIndicator, ":outputfscyear" => $outputfscyear, ":outputduration" => $outputduration, ":budget" => $outputbudget));
            $last_id = $db->lastInsertId();
            // echo $last_id;

            $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_outputs`(outputid, indicator, data_source, data_collection_method, monitoring_frequency, 
			report_user, reporting_timeline, date_created, created_by) VALUES(:output, :indicator, :data_source, :data_collection_method, :monitoring_frequency, :report_user, :reporting_timeline, :date_added,:added_by)");
            $result1  = $insertSQL1->execute(array(":output" => $last_id, ":indicator" => $outputIndicator, ":data_source" => $outputdataSource, ":data_collection_method" => $outputDataCollectionMethod, ":monitoring_frequency" => $outputMonitorigFreq, ":report_user" => $outputReportUser, ":reporting_timeline" => $outputReportingTimeline, ":date_added" => $datecreated, ":added_by" => $createdby));

            if (isset($_POST['outputrisk'])) {
                for ($i = 0; $i < count($_POST['outputrisk']); $i++) {
                    $riskid = $_POST['outputrisk'][$i];
                    $type = 3;
                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_output_risks`(rskid, outputid, type) VALUES(:rskid, :outputid, :type )");
                    $result1  = $insertSQL1->execute(array(":rskid" => $riskid, ":outputid" => $last_id, ":type" => $type));
                }
            }
        }
        echo json_encode($last_id);
    }


    if (isset($_POST['editoutput'])) {
        $progid = $_POST['myprogid'];
        $output = $_POST['outputids'];
        $opid = $_POST['opid'];

        if (isset($_POST['projid'])) {
            $projid = $_POST['projid'];
        } else {
            $projid = null;
        }

        $outputfscyear = $_POST['outputfscyear'];
        $outputduration = $_POST['outputduration'];
        $outputIndicator = $_POST['indicatorid'];
        $outputdataSource = implode(",", $_POST['outputdataSource']);
        $outputDataCollectionMethod = $_POST['outputDataCollectionMethod'];
        $outputMonitorigFreq = $_POST['outputMonitorigFreq'];
        // $outputresponsible = $_POST['outputresponsible'];
        $outputReportUser = implode(",", $_POST['outputReportUser']);
        $outputReportingTimeline = implode(",", $_POST['outputReportingTimeline']);
        $datecreated = date("Y-m-d");
        // $createdby = $_POST['user_name'];
        $createdby = 1;
        $outputbudget = $_POST['outputbudget'];

        $insertSQL1 = $db->prepare("UPDATE  tbl_project_details SET   year=:outputfscyear, duration=:outputduration, budget=:budget WHERE id =:opid");
        $result1  = $insertSQL1->execute(array(":outputfscyear" => $outputfscyear, ":outputduration" => $outputduration, ":budget" => $outputbudget, ":opid" => $opid));

        $insertSQL1 = $db->prepare("UPDATE tbl_project_outputs SET data_source=:data_source, data_collection_method=:data_collection_method, monitoring_frequency=:monitoring_frequency, report_user=:report_user, reporting_timeline=:reporting_timeline, date_changed=:date_added, changed_by=:added_by WHERE outputid=:opid ");
        $result1  = $insertSQL1->execute(array(":data_source" => $outputdataSource, ":data_collection_method" => $outputDataCollectionMethod, ":monitoring_frequency" => $outputMonitorigFreq, ":report_user" => $outputReportUser, ":reporting_timeline" => $outputReportingTimeline, ":date_added" => $datecreated, ":added_by" => $createdby, ":opid" => $opid));

        if (isset($_POST['outputrisk'])) {
            $deleteQuery = $db->prepare("DELETE FROM `tbl_output_risks` WHERE outputid=:opid");
            $results = $deleteQuery->execute(array(':opid' => $opid));

            for ($i = 0; $i < count($_POST['outputrisk']); $i++) {
                $riskid = $_POST['outputrisk'][$i];
                $type = 3;
                $insertSQL1 = $db->prepare("INSERT INTO `tbl_output_risks`(projid, rskid, outputid, type) VALUES(:projid,:rskid, :outputid, :type )");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":rskid" => $riskid, ":outputid" => $opid, ":type" => $type));
            }
        }
        echo json_encode($opid);
    }

    if (isset($_POST["deleteItem"])) {
        $itemid = $_POST['itemId'];
        $deleteQuery = $db->prepare("DELETE FROM `tbl_project_details` WHERE id=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid));

        $deleteQuery = $db->prepare("DELETE FROM `tbl_project_outputs` WHERE outputid=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid));

        $deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projoutputid=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid));

        $deleteQuery = $db->prepare("DELETE FROM `tbl_output_risks` WHERE outputid=:itemid");
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

            $deleteQuery = $db->prepare("DELETE FROM `tbl_project_outputs` WHERE outputid=:itemid");
            $results = $deleteQuery->execute(array(':itemid' => $itemids[$i]));

            $deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projoutputid=:itemid");
            $results = $deleteQuery->execute(array(':itemid' => $itemids[$i]));

            $deleteQuery = $db->prepare("DELETE FROM `tbl_output_risks` WHERE outputid=:itemid");
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

    if (isset($_POST['getTargetDiv'])) {
        $outputIds = $_POST['outputArr'];
        $programid = $_POST['prograid'];
        $counter = 0;
        $Targets = '';

        for ($i = 0; $i < count($outputIds); $i++) {
            //get project output details 
            $query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE id = '$outputIds[$i]' ");
            $query_OutputData->execute();
            $rows_OutpuData = $query_OutputData->rowCount();
            $row_OutputData =  $query_OutputData->fetch();
            $counter++;

            do {
                $indicator = $row_OutputData['indicator'];
                $query_rsIndicator = $db->prepare("SELECT indname, indid FROM tbl_indicator WHERE indid ='$indicator'");
                $query_rsIndicator->execute();
                $row_rsIndicator = $query_rsIndicator->fetch();
                $indname = $row_rsIndicator['indname'];

                $query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator  INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.unit WHERE tbl_indicator.indid ='$indicator' AND baseline=1 AND indcategory='Output' ");
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

                $durationinmonths = floor($projoutputDValue / 30.4);
                $remainingdays = $projoutputDValue % 365;

                if ($remainingdays > 0) {
                    $durationinmonths = $durationinmonths + 1;
                }

                $quaters = round($durationinmonths / 3);
                $remainderq = $durationinmonths % 3;

                if ($quaters == 0) {
                    $quaters = $quaters + 1;
                }

                $startyear = $projstartyear + 1;
                $noofyears = $quaters / 4;
                $spanYear = '';
                $TargetPlan = '';
                $containerTH = '';
                $containerTH2 = '';
                $projoutputValue = $outputIds[$i];
                $projindicator = $indicator;
                $containerTB  = "<tr>";
                $contain  = "";

                for ($rowno = 0; $rowno < $noofyears; $rowno++) {
                    $query_rsprogTarget =  $db->prepare("SELECT target FROM tbl_progdetails WHERE indicator ='$indicator' and progid ='$programid' and year ='$projstartyear'");
                    $query_rsprogTarget->execute();
                    $row_rsprogTarget = $query_rsprogTarget->fetch();
                    $totalRows_rsprogTarget = $query_rsprogTarget->rowCount();
                    $progTarget = $row_rsprogTarget['target'];

                    $query_rsprojTarget =  $db->prepare("SELECT SUM(target) as target FROM tbl_project_output_details WHERE indicator ='$indicator' and progid ='$programid' and year ='$projstartyear'");
                    $query_rsprojTarget->execute();
                    $row_rsprojTarget = $query_rsprojTarget->fetch();
                    $totalRows_rsprojTarget = $query_rsprojTarget->rowCount();
                    $projTarget = $row_rsprojTarget['target'];
                    $remaining = $progTarget - $projTarget;

                    $arr = '';
                    if ($remaining > 0) {
                        $arr =  $remaining;
                    } else {
                        $arr =  0;
                    }

                    $spanYear .=
                        '<div id="proj' .  $projstartyear .  "/" .  $startyear . '" class="col-md-4"> 
                        Project 
                    <strong> ' .  $projstartyear . "/" . $startyear . ' </strong> Annual Target Plan<br>
                        <span id="projmsg' . $projstartyear .  $counter . '" style="color:red">' . $arr . '</span></div>';
                    $TargetPlan .=
                        '<input type="hidden" class="projdurationerow' . $counter . '" name="projTragetplan[]" value="' . $projstartyear . '" />';
                    if ($quaters > 4) {
                        $containerTH .=
                            '<th colspan="4">' . $projstartyear .  "/" . $startyear . ' 
                        <input type="hidden" class="output' . $output . '"  name="projoutputYearValue[]" value="' . $projstartyear . '" /> 
                        <input type="hidden" name="targetPlan[]" value="' . $arr  .  '" id="targetVal' . $projstartyear .  $counter . '" >
                        Remaining Target <span id="targetmsg' . $projstartyear .  $counter . '" style="color:red"> (' . $arr . ')</span>  </th>';

                        for ($j = 0; $j < 4; $j++) {
                            $k = $j + 1;
                            $containerTH2 .=
                                '<th width="300px"> Quarter ' .  $k .
                                ' Target</th>';
                            $containerTB .=
                                '<td width="300px"> <input type="hidden"  name="projoutputValue[]" value="' . $projoutputValue . '" />  ' .
                                '<input type="number" onchange=targetsBlur("' .  $counter  .  '","' .  $projstartyear . '") onkeyup=targetsBlur("' .  $counter  .  '","' .  $projstartyear . '")
                            name="target[]" id="' .  $projstartyear . '" placeholder="Enter" data-id="' .  $projstartyear . '"
                            class="form-control selectSource' . $counter .  $projstartyear .
                                " selected" . $output . $projstartyear . '" required>' .
                                "</td>";
                            $contain  .=
                                '<input type="hidden" name="projyear[]"   value="' . $projstartyear .  '" />';
                        }
                    } else {
                        $containerTH   .=
                            '<th colspan="' . $quaters .     '" >' . $projstartyear . "/" . $startyear .
                            '
							<input type="hidden" class="output' . $output . '"  name="projoutputYearValue[]" value="' . $projstartyear . '" /> 
                            <input type="hidden" name="targetPlan[]" value="' . $arr  .  '" id="targetVal' . $projstartyear .  $counter . '" >
                            Remaining Target <span id="targetmsg' . $projstartyear .  $counter . '" style="color:red"> (' . $arr . ')</span> </th>';
                        for ($j = 0; $j < $quaters; $j++) {
                            $k = $j + 1;
                            $containerTH2 .=
                                '<th width="300px"> Quarter ' .  $k .
                                ' Target</th>';
                            $containerTB  .=
                                '<td width="300px">
								<input type="hidden"  name="projoutputValue[]" value="' .  $projoutputValue . '" /> ' .
                                '<input type="number" name="target[]" id="' . $projstartyear . '"  onchange=targetsBlur("' .  $counter  .  '","' .  $projstartyear . '") onkeyup=targetsBlur("' .  $counter  .  '","' .  $projstartyear . '")
                            placeholder="Enter" data-id="' . $projstartyear . '" class="form-control selectSource' . $counter . $projstartyear .
                                " selected" . $output . $projstartyear . '" required>
                            </td>';
                            $contain  .= '<input type="hidden"  name="projyear[]" value="' . $projstartyear . '" />';
                        }
                    }
                    $quaters = $quaters - 4;
                    $projstartyear = $projstartyear + 1;
                    $startyear = $startyear + 1;
                }
                $containerTH2 . $containerTB  .= "</tr>";

                $Targets  .= '
                <div class="row clearfix " id="Targetrowcontainer' . trim($outputIds[$i]) . '">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
								<div class="col-md-5 clearfix" style="margin-top:5px; margin-bottom:5px">
									<h5 style="color:#FF5722"><strong> Output: ' . $outputName . '</strong></h5>
                                </div>
								<div class="col-md-5 clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <h5 style="color:#FF5722"><strong> Indicator: ' . $indname . '</strong></h5>
                                </div>
                                <div class="col-md-2 clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <h5 style="color:#FF5722"><strong> Unit : ' . $unit . '</strong></h5>
                                </div>
                            </div>
                            <div class="body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="spanYears">
                                            ' . $TargetPlan . '
                                            ' . $contain . '
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                                        <thead>
                                            <tr>
                                                ' . $containerTH . '
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ' . $containerTH2 . $containerTB . '
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            } while ($row_OutputData =  $query_OutputData->fetch());
        }
        echo $Targets;
    }

    if (isset($_POST['getImplementingPartner'])) {
        $leadImplementor = $_POST['leadImplementor'];
        $query_rsPartner =  $db->prepare("SELECT * FROM tbl_partners");
        $query_rsPartner->execute();
        echo '<option value="">...Select from list...</option><option value="0">Not Applicable</option>';
        while ($row_rsPartner = $query_rsPartner->fetch()) {
            if ($row_rsPartner['ptnid']  != $leadImplementor) {
                echo '<option value="' . $row_rsPartner['ptnid'] . '"> ' . $row_rsPartner['partnername'] . '</option>';
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


            $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorID' ");
            $query_Indicator->execute();
            $row = $query_Indicator->fetch();
            $indname = $row['indname'];

            $query_opunit = $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.unit WHERE i.indid ='$indicatorID'");
            $query_opunit->execute();
            $row = $query_opunit->fetch();
            $opunit = $row['unit'];

            $query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$oipid' ");
            $query_out->execute();
            $row_out = $query_out->fetch();
            $outputName = $row_out['output'];

            $query_rsOutputDetails =  $db->prepare("SELECT * FROM tbl_project_outputs WHERE outputid ='$opid'");
            $query_rsOutputDetails->execute();
            $row_rsOutputDetails = $query_rsOutputDetails->fetch();
            $totalRows_rsOutputDetails = $query_rsOutputDetails->rowCount();
            $outputids = $row_rsOutputDetails['outputid'];

            $outputdata_source =  explode(",", $row_rsOutputDetails['data_source']);
            $outputSource = [];
            for ($i  = 0; $i < count($outputdata_source); $i++) {
                $query_rsOutputSource =  $db->prepare("SELECT * FROM tbl_data_source WHERE id ='$outputdata_source[$i]'");
                $query_rsOutputSource->execute();
                $row_rsOutputSource = $query_rsOutputSource->fetch();
                $totalRows_rsOutputSource = $query_rsOutputSource->rowCount();
                $outputSource[] = $row_rsOutputSource['source'];
            }

            $outputdata_collection_method = $row_rsOutputDetails['data_collection_method'];
            $query_rsOutputCollectionMethod =  $db->prepare("SELECT * FROM tbl_datagatheringmethods WHERE id='$outputdata_collection_method'");
            $query_rsOutputCollectionMethod->execute();
            $row_rsOutputCollectionMethod = $query_rsOutputCollectionMethod->fetch();
            $totalRows_rsOutputCollectionMethod = $query_rsOutputCollectionMethod->rowCount();
            $outputCollectionMethod = $row_rsOutputCollectionMethod['methods'];

            $outputmonitoring_frequency = $row_rsOutputDetails['monitoring_frequency'];
            $query_rsOutputevaluationFreq =  $db->prepare("SELECT * FROM  tbl_datacollectionfreq WHERE fqid ='$outputmonitoring_frequency'");
            $query_rsOutputevaluationFreq->execute();
            $row_rsOutputevaluationFreq = $query_rsOutputevaluationFreq->fetch();
            $totalRows_rsOutputevaluationFreq = $query_rsOutputevaluationFreq->rowCount();
            $Outputevaluationfreq = $row_rsOutputevaluationFreq['frequency'];

            $outputresponsible = $row_rsOutputDetails['responsible'];
            $query_rsOutputResponsible =  $db->prepare("SELECT * FROM  tbl_projteam2 WHERE ptid ='$outputresponsible'");
            $query_rsOutputResponsible->execute();
            $row_rsOutputResponsible = $query_rsOutputResponsible->fetch();
            $totalRows_rsOutputResponsible  = $query_rsOutputResponsible->rowCount();
            $OutputResponsible = $row_rsOutputResponsible['fullname'];

            $outputreport_user =  explode(",", $row_rsOutputDetails['report_user']);
            $output_user = [];
            for ($i  = 0; $i < count($outputreport_user); $i++) {
                $query_rsOutput_User =  $db->prepare("SELECT * FROM  tbl_projteam2 WHERE ptid ='$outputreport_user[$i]'");
                $query_rsOutput_User->execute();
                $row_rsOutput_User = $query_rsOutput_User->fetch();
                $totalRows_rsOutput_User  = $query_rsOutput_User->rowCount();
                if ($totalRows_rsOutput_User > 0) {
                    do {
                        $output_user[] = $row_rsOutput_User['fullname'];
                    } while ($row_rsOutput_User = $query_rsOutput_User->fetch());
                }
            }

            $outputreporting_timeline =  explode(",", $row_rsOutputDetails['reporting_timeline']);
            $output_timeline = [];

            for ($i  = 0; $i < count($outputreporting_timeline); $i++) {
                $query_rsOutput_reportingTimeline =  $db->prepare("SELECT * FROM  tbl_datacollectionfreq WHERE fqid ='$outputreporting_timeline[$i]'");
                $query_rsOutput_reportingTimeline->execute();
                $row_rsOutput_reportingTimeline = $query_rsOutput_reportingTimeline->fetch();
                $totalRows_rsOutput_reportingTimeline  = $query_rsOutput_reportingTimeline->rowCount();
                if ($totalRows_rsOutput_reportingTimeline > 0) {
                    do {
                        $output_timeline[] = $row_rsOutput_reportingTimeline['frequency'];
                    } while ($row_rsOutput_reportingTimeline = $query_rsOutput_reportingTimeline->fetch());
                }
            }

            $query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE  id='$outcomeYear'");
            $query_rsYear->execute();
            $row_rsYear = $query_rsYear->fetch();
            $fscyear = $row_rsYear['year'];

            $query_rsRisk =  $db->prepare("SELECT category FROM tbl_output_risks INNER JOIN tbl_projrisk_categories ON tbl_projrisk_categories.rskid = tbl_output_risks.rskid WHERE tbl_output_risks.type=3  AND outputId = '$opid' ");
            $query_rsRisk->execute();
            $outputrisks = [];
            while ($row_rsRisk = $query_rsRisk->fetch()) {
                $outputrisks[] = $row_rsRisk['category'];
            }

            $Targets .= '
            <tr>
                <td>' . $counter . '  </td> 
                <td>' . $outputName . '  </td> 
                <td>' . $indname . '  </td> 
                <td>' . $opunit . '  </td> 
                <td>' . $fscyear . '  </td> 
                <td>' . number_format($outcomeDuration) . ' Days</td> 
                <td>' . number_format($outputBudget, 2) . '  </td> 
                <td>' . implode(",", $outputSource) . '  </td> 
                <td>' . $outputCollectionMethod . '  </td> 
                <td>' . $Outputevaluationfreq . '  </td> 
                <td>' . implode(",", $output_user) . '  </td> 
                <td>' . implode(",", $output_timeline) . '  </td> 
                <td>' . implode(",", $outputrisks) . '</td>
                <td>0 ' . $opunit . '  </td>   
            </tr> 
            ';
        }

        $type = '
         <div class="table-responsive">
            <table summary="" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Output Name </th>
                        <th>Indicator </th>
                        <th>Unit of Measure</th>
                        <th>Start Year</th>
                        <th>Duration </th>
                        <th>Budget (Ksh)</th>
                        <th>Data Source </th>
                        <th>Data Collection Method</th>
                        <th>Monitoring Frequency </th>
                        <th>Report User</th>
                        <th>Reporting Timeline </th> 
                        <th>Output Risks</th> 
                        <th>Project Output Baseline</th> 
                    </tr>
                </thead>
                <tbody>
                     ' . $Targets . '
                </tbody>
            </table>
        </div> ';
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
        $query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid ='$progid'");
        $query_rsFunding->execute();
        $row_rsFunding = $query_rsFunding->fetch();
        $totalRows_rsFunding = $query_rsFunding->rowCount();
        echo '<option value="">Select Financier from list</option>';
        do {
            $source = $row_rsFunding['sourceid'];
            $progfundid = $row_rsFunding['id'];
            $rate = $row_rsFunding['rate'];
            $progfunds = $row_rsFunding['amountfunding'] * $rate;

            $query_rsprojFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_myprojfunding WHERE progid ='$progid' and progfundid ='$progfundid' ");
            $query_rsprojFunding->execute();
            $row_rsprojFunding = $query_rsprojFunding->fetch();
            $totalRows_rsprojFunding = $query_rsprojFunding->rowCount();

            $projfunds = $row_rsprojFunding['amountfunding'];
            $remaining = $progfunds - $projfunds;

            if ($remaining > 0) {
                if ($row_rsFunding['sourcecategory']  == "donor") {
                    $query_rsDonor = $db->prepare("SELECT * FROM tbl_donors WHERE dnid='$source'");
                    $query_rsDonor->execute();
                    $row_rsDonor = $query_rsDonor->fetch();
                    $totalRows_rsDonor = $query_rsDonor->rowCount();
                    $donor = $row_rsDonor['donorname'];
                    echo '<option value="' . $row_rsFunding['id'] . '">' . $donor . '</option>';
                } else if ($row_rsFunding['sourcecategory']  == "others") {
                    $query_rsFunder = $db->prepare("SELECT * FROM tbl_funder WHERE id='$source'");
                    $query_rsFunder->execute();
                    $row_rsFunder = $query_rsFunder->fetch();
                    $totalRows_rsFunder = $query_rsFunder->rowCount();
                    $funder = $row_rsFunder['name'];
                    echo '<option value="' . $row_rsFunding['id'] . '">' . $funder . '</option>';
                }
            }
        } while ($row_rsFunding = $query_rsFunding->fetch());
    }

    if (isset($_POST['getImpactBaseline'])) {
        $impactIndicator = $_POST['impactIndicator'];
        $ImpactProjState = $_POST['ImpactProjState'];
        $tottalBaseline = 0;
        $query_rsUnit = $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.unit WHERE i.indid ='$impactIndicator'");
        $query_rsUnit->execute();
        $row_rsUnit = $query_rsUnit->fetch();
        $impunits = $row_rsUnit['unit'];
        $allindicators = '
        <div id="getImpactBaselines">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="impactinddetails" style="width:100%">
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="68%">Project ' . $level3label . '</th>
                        <th width="28%">Indicator Baseline (' . $impunits . ')*</th> 
                    </tr>
                </thead>
                <tbody id="">';
        $counter = 1;
        for ($i = 0; $i < count($ImpactProjState); $i++) {
            $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` 
            INNER JOIN tbl_indicator_details ON tbl_indicator_details.indid = tbl_indicator.indid WHERE `indcategory` = 'Impact' and `active` = '1' AND  level3='$ImpactProjState[$i]'  AND tbl_indicator.indid='$impactIndicator'");
            $query_indicator->execute();
            $row_indicator = $query_indicator->fetch();
            $totalRows_rsIndicator = $query_indicator->rowCount();

            if ($totalRows_rsIndicator > 0) {
                $query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$ImpactProjState[$i]' ");
                $query_rslga->execute();
                $row_rslga = $query_rslga->fetch();
                $level2 = $row_rslga['state'];
                $level2id = $row_rslga['id'];
                $baseline = $row_indicator['basevalue'];
                $tottalBaseline = $tottalBaseline + $baseline;
                $allindicators .= ' 
                    <tr>
                        <td>
                        ' . $counter++ . '
                        </td>
                        <td>
                            <input type="hidden" name="impactState[]" id="impactState" value="' . $level2id . '" class="form-control" required="required" >
                            <input type="text" name="impactStates[]" id="impactStates" value="' . $level2 . '" class="form-control impactStates" required="required" disabled>
                        </td>
                        <td>
                        <input type="hidden" name="impactBaseline[]" id="impactBaseline" value="' . $baseline  . '" class="form-control" required="required" >
                        <input type="number" name="impactBaselines[]" id="impactBaselines" value="' . $baseline  . '" class="form-control impactBaselines" required="required" disabled>
                        </td> 
                    </tr>';
            }
        }

        $allindicators .= '<tr><td colspan="2"><strong>Total Baseline Value</strong></td> <td><strong>' . number_format($tottalBaseline, 2)  . '</strong></td></tr></tbody>
        </table>
        </div> </div>';
        echo $allindicators;
    }

    if (isset($_POST['getImpactIndUnit'])) {
        $impactIndid = $_POST['impactIndid'];

        $query_rsUnit = $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.unit WHERE i.indid ='$impactIndid'");
        $query_rsUnit->execute();
        $row_rsUnit = $query_rsUnit->fetch();
        $impunits = $row_rsUnit['unit'];

        echo $impunits;
    }

    if (isset($_POST['getImpactUnit'])) {
        $impactInd = $_POST['impactInd'];

        $query_rsUnit = $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.unit WHERE i.indid ='$impactInd'");
        $query_rsUnit->execute();
        $row_rsUnit = $query_rsUnit->fetch();
        $rsUnit = "(" . $row_rsUnit['unit'] . ")";
        echo $rsUnit;
    }

    if (isset($_POST['getOutcomeBaseline'])) {
        $outcomeIndicator = $_POST['outcomeIndicator'];
        $outcomeProjState = $_POST['outcomeProjState'];
        $tottalBaseline = 0;
        $query_rsUnit = $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.unit WHERE i.indid ='$outcomeIndicator'");
        $query_rsUnit->execute();
        $row_rsUnit = $query_rsUnit->fetch();
        $ocunits = $row_rsUnit['unit'];
        $allindicators = '
        <div id="getOutcomeBaselines">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="outcomeinddetails" style="width:100%">
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="68%">Project ' . $level3label . '</th>
                        <th width="28%">Indicator Baseline (' . $ocunits . ')*</th> 
                    </tr>
                </thead>
                <tbody id="">';
        $counter = 1;
        for ($i = 0; $i < count($outcomeProjState); $i++) {
            $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` INNER JOIN tbl_indicator_details ON tbl_indicator_details.indid = tbl_indicator.indid WHERE `indcategory` = 'Outcome' and `active` = '1' AND  level3='$outcomeProjState[$i]' AND tbl_indicator.indid='$outcomeIndicator'");
            $query_indicator->execute();
            $row_indicator = $query_indicator->fetch();
            $totalRows_rsIndicator = $query_indicator->rowCount();

            if ($totalRows_rsIndicator > 0) {
                $query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$outcomeProjState[$i]' ");
                $query_rslga->execute();
                $row_rslga = $query_rslga->fetch();
                $level2 = $row_rslga['state'];
                $level2id = $row_rslga['id'];
                $baseline = $row_indicator['basevalue'];
                $tottalBaseline = $tottalBaseline + $baseline;

                $allindicators .= '
                    <tr>
                        <td>
                        ' . $counter++ . '
                        </td>
                        <td>
                            <input type="hidden" name="outcomeState[]" id="outcomeState" value="' . $level2id . '" class="form-control" required="required" >
                            <input type="text" name="outcomeStates[]" id="outcomeStates" value="' . $level2 . '" class="form-control outcomeStates" required="required" disabled>
                        </td>
                        <td>
                        <input type="hidden" name="outcomeBaseline[]" id="outcomeBaseline" value="' . $baseline  . '" class="form-control" required="required" >
                        <input type="number" name="outcomeBaselines[]" id="outcomeBaselines" value="' . $baseline  . '" class="form-control outcomeBaselines" required="required" disabled>
                        </td>
                    </tr>';
            }
        }

        $allindicators .= '<tr><td colspan="2"><strong>Total Baseline Value</strong></td> <td><strong>' . number_format($tottalBaseline, 2)  . '</strong></td></tr>
        </tbody>
        </table><input type="hidden" name="ocunits" id="ocunits" value="' . $ocunits  . '">
        </div></div>';
        echo $allindicators;
    }

    // if (isset($_POST['getOutputBaseline'])) {
    //     $outputIndicator = $_POST['outputIndicator'];
    //     $OutputProjState = $_POST['ouputProjstate'];
    //     $tottalBaseline = 0;

    // 	$query_rsUnit = $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.unit WHERE i.indid ='$outputIndicator'");
    // 	$query_rsUnit->execute();
    // 	$row_rsUnit = $query_rsUnit->fetch();
    // 	$opUnits = $row_rsUnit['unit'];
    //     $allindicators = '

    //     <div class="table-responsive">
    //         <table class="table table-bordered table-striped table-hover" id="outputinddetails" style="width:100%">
    //             <thead>
    //                 <tr>
    //                     <th width="4%">#</th>
    //                     <th width="68%">Project '.$level3label.'</th>
    //                     <th width="28%">Indicator Baseline ('.$opUnits.')*</th> 
    //                 </tr>
    //             </thead>
    //             <tbody id="">';
    //     $counter = 1;
    //     for ($i = 0; $i < count($OutputProjState); $i++) {
    //         $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` INNER JOIN tbl_indicator_details ON tbl_indicator_details.indid = tbl_indicator.indid WHERE `indcategory` = 'Output' and `active` = '1' AND  level3='$OutputProjState[$i]' AND tbl_indicator.indid='$outputIndicator'");
    //         $query_indicator->execute();
    //         $row_indicator = $query_indicator->fetch();
    //         $totalRows_rsIndicator = $query_indicator->rowCount();

    //         if ($totalRows_rsIndicator > 0) {
    //             $query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$OutputProjState[$i]' ");
    //             $query_rslga->execute();
    //             $row_rslga = $query_rslga->fetch();
    //             $level3 = $row_rslga['state'];
    //             $level3id = $row_rslga['id'];
    //             $baseline = $row_indicator['basevalue'];
    //             $tottalBaseline = $tottalBaseline + $baseline;

    //             $allindicators .= '
    //                 <tr>
    //                     <td>
    //                     ' . $counter++ . '
    //                     </td>
    //                     <td>
    //                     <input type="hidden" name="outputState[]" id="outputState" value="' . $level3id . '" class="form-control" required="required"  >
    //                     <input type="text" name="outputStates[]" id="outputState" value="' . $level3 . '" class="form-control" required="required" disabled >
    //                     </td>
    //                     <td> 
    //                     <input type="hidden" name="outputBaseline[]" id="outputBaseline" value="' . $baseline  . '" class="form-control" required="required"  >
    //                     <input type="number" name="outputBaselineS[]" id="outputBaselineS" value="' . $baseline  . '" class="form-control" required="required"  disabled>
    //                     </td>
    //                 </tr>';
    //         }
    //     }
    //     $allindicators .= '<tr><td colspan="2"><strong>Total Baseline Value</strong></td> <td><strong>' . number_format($tottalBaseline)  . '</strong></td></tr>
    //     </tbody>
    //     </table>
    //     </div>';
    //     echo $allindicators;
    // }

    if (isset($_POST['getOCIndUnit'])) {
        $ocIndUnit = $_POST['ocIndUnit'];

        $query_rsUnit = $db->prepare("SELECT u.unit FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.unit WHERE i.indid ='$ocIndUnit'");
        $query_rsUnit->execute();
        $row_rsUnit = $query_rsUnit->fetch();
        $rsUnit = "(" . $row_rsUnit['unit'] . ")";
        echo $rsUnit;
    }

    // edit functionality 
    if (isset($_POST['getprojectOutputData'])) {
        $outputids  = $_POST['outputids'];
        $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  id='$outputids' ");
        $query_rsOutput->execute();
        $row_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        $opid = $row_rsOutput['id'];
        $oipid = $row_rsOutput['outputid']; //outputid from program table 
        $indicatorid = $row_rsOutput['indicator'];
        $outputYear = $row_rsOutput['year'];

        $outputDuration = $row_rsOutput['duration'];
        $outputBudget = $row_rsOutput['budget'];
        $programid = $row_rsOutput['progid'];

        $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id='$outputYear'");
        $query_rsYear->execute();
        $row_rsYear = $query_rsYear->fetch();
        $fscyear = $row_rsYear['yr'];

        $query_rsprogBudget = $db->prepare("SELECT budget FROM tbl_progdetails WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$fscyear'");
        $query_rsprogBudget->execute();
        $row_rsprogBudget = $query_rsprogBudget->fetch();
        $totalRows_rsprogBudget = $query_rsprogBudget->rowCount();
        $amountprogBudget = $row_rsprogBudget['budget'];

        $query_rsprojBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$outputYear'");
        $query_rsprojBudget->execute();
        $row_rsprojBudget = $query_rsprojBudget->fetch();
        $totalRows_rsprojBudget = $query_rsprojBudget->rowCount();
        $amountprojBudget = $row_rsprojBudget['budget'];
        $remainingBudget =  $amountprogBudget - $amountprojBudget + $outputBudget;

        //query projectoutputs 
        $query_rsOutputDetails =  $db->prepare("SELECT * FROM  tbl_project_outputs WHERE outputid ='$opid'");
        $query_rsOutputDetails->execute();
        $row_rsOutputDetails = $query_rsOutputDetails->fetch();
        $totalRows_rsOutputDetails = $query_rsOutputDetails->rowCount();
        $outputdata_collection_method = $row_rsOutputDetails['data_collection_method'];
        $outputmonitoring_frequency = $row_rsOutputDetails['monitoring_frequency'];
        $report_user = $row_rsOutputDetails['report_user'];
        $reporting_timeline = $row_rsOutputDetails['reporting_timeline'];
        $sourceofdata = $row_rsOutputDetails['data_source'];

        //output risks 
        $query_rsRisk =  $db->prepare("SELECT c.rskid as rsid, category FROM tbl_output_risks r INNER JOIN tbl_projrisk_categories c ON c.rskid = r.rskid WHERE outputId = '$opid'");
        $query_rsRisk->execute();
        $row_rsRisk = $query_rsRisk->fetch();
        $outputRisks = [];

        do {
            $outputRisks[] = $row_rsRisk['rsid'];
        } while ($row_rsRisk = $query_rsRisk->fetch());

        $oprisks =  implode(",", $outputRisks);

        $data  = array("year" => $outputYear, "duration" => $outputDuration, "outputBudget" => $outputBudget, "sourceofData" =>  $sourceofdata, "datacollec" => $outputdata_collection_method, "monitoringfreq" => $outputmonitoring_frequency, "report_user" => $report_user, "reporting_timeline" => $reporting_timeline, "oprisks" =>   $oprisks, "opid" => $opid, "outputDuration" => $outputDuration, "remainingBudget" => $remainingBudget);
        echo json_encode($data);
    }
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    echo $ex->getMessage();
}
