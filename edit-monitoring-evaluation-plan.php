<?php
    try {

require('includes/head.php');
if ($permission) {

        $results = "";
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }

        if (isset($_GET['projid'])) {
            $projid = $_GET['projid'];

            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
            $query_rsProjects->execute(array(":projid" => $projid));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();
            $progid = $row_rsProjects['progid'];
            $report_user = explode(",", $row_rsProjects['mne_report_users']);
            $responsible = $row_rsProjects['mne_responsible'];
            $outcome  = $row_rsProjects['outcome'];
            $indid  = $row_rsProjects['outcome_indicator'];

            $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator`   WHERE indid=:indid ");
            $query_indicator->execute(array(":indid" => $indid));
            $row_indicator = $query_indicator->fetch();
            $ocunitid = $row_indicator['indicator_unit'];
            $ocindid = $row_indicator['indid'];
            $outcomeIndicator = $row_indicator['indicator_name'];
            $calcid = $row_indicator['indicator_calculation_method'];

            $query_Indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
            $query_Indicator_cal->execute(array(':calcid' => $calcid));
            $row_cal = $query_Indicator_cal->fetch();
            $occalc_method = $row_cal['method'];

            $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
            $query_Indicator->execute(array(":unit" => $ocunitid));
            $row = $query_Indicator->fetch();
            $ocunitofmeasure = $row['unit'];

            //Outcome  details 
            $query_rsOutcomeDetails =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE  projid='$projid' ");
            $query_rsOutcomeDetails->execute();
            $row_rsOutcomeDetails = $query_rsOutcomeDetails->fetch();
            $Outcomedata_source = $row_rsOutcomeDetails['data_source'];
            $Outcomeevaluation_frequency = $row_rsOutcomeDetails['evaluation_frequency'];

            //Outcome  evaluation questions 
            $query_outcomeevalqstns =  $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE  projid='$projid'");
            $query_outcomeevalqstns->execute();
            $count_outcomeevalqstns = $query_outcomeevalqstns->rowCount();
        }


        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addmefrm")) {
            $createdby  = $user_name;
            $datecreated  = date("Y-m-d");

            if (isset($_POST['responsible'])) {
                $responsible = $_POST['responsible'];
                $outcome = $_POST['outcome'];
                $outcomeIndicator = $_POST['outcomeIndicator'];
                $report_user = implode(",", $_POST['reportUser']);
                $insertSQL1 = $db->prepare("UPDATE tbl_projects SET outcome = :outcome, outcome_indicator = :outcomeIndicator, mne_responsible = :responsible, mne_report_users=:reportUsers WHERE  projid=:projid");
                $result1  = $insertSQL1->execute(array(":outcome" => $outcome, ":outcomeIndicator" => $outcomeIndicator, ":responsible" => $responsible, ":reportUsers" => $report_user, ":projid" => $projid));
            }

            if (isset($_POST['outcomedataSource'])) {
                $outcomedataSource = $_POST['outcomedataSource'];
                $outcomeEvaluationFreq = $_POST['outcomeEvaluationFreq'];

                $type = 2;
                $deleteQuery = $db->prepare("DELETE FROM `tbl_projectrisks` WHERE projid=:projid and type=:type");
                $results = $deleteQuery->execute(array(':projid' => $projid, ':type' => $type));

                $insertSQL1 = $db->prepare("UPDATE `tbl_project_expected_outcome_details` SET   data_source=:data_source,evaluation_frequency=:evaluation_frequency, changed_by=:changed_by,  date_changed=:date_changed WHERE projid=:projid");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":data_source" => $outcomedataSource,  ":evaluation_frequency" => $outcomeEvaluationFreq, ":changed_by" => $createdby, ":date_changed" => $datecreated, ":projid" => $projid));

                if (isset($_POST['outcomerisk'])) {
                    for ($i = 0; $i < count($_POST['outcomerisk']); $i++) {
                        $riskid = $_POST['outcomerisk'][$i];
                        $assumption = $_POST['outcome_assumptions'][$i];
                        $insertSQL1 = $db->prepare("INSERT INTO `tbl_projectrisks`(projid, rskid, type, assumption) VALUES(:projid, :rskid, :type, :assumption)");
                        $result1  = $insertSQL1->execute(array(":projid" => $projid, ":rskid" => $riskid, ":type" => $type, ":assumption" => $assumption));
                    }
                }

                if ($outcomedataSource == 1) {
                    $deleteQuery = $db->prepare("DELETE FROM `tbl_project_outcome_evaluation_questions` WHERE projid=:projid");
                    $results = $deleteQuery->execute(array(':projid' => $projid));

                    for ($j = 0; $j < count($_POST['questions']); $j++) {
						$question = $_POST['questions'][$j];

						$insertSQL1 = $db->prepare("INSERT INTO `tbl_project_outcome_evaluation_questions`(projid, question) VALUES(:projid, :question)");
						$result1  = $insertSQL1->execute(array(":projid" => $projid, ":question" => $question));
                    }
                }
            }

            if ($result1) {
				require 'PHPMailer/PHPMailerAutoload.php';
					
				$query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid = :projid");
				$query_proj->execute(array(":projid" => $projid));
				$row_proj = $query_proj->fetch();
				$projcode = $row_proj["projcode"];
				$projname = $row_proj["projname"];
				
				$query_timeline = $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'Baseline' AND workflow = 9 AND stage = 1");
				$query_timeline->execute();
				$row_timeline = $query_timeline->fetch();
				$time = $row_timeline["time"];
				$unit = $row_timeline["units"];
									 
				// today's date
				$Date = date("Y-m-d");
				 
				// Add days to date and display it
				$duedate = date('Y-m-d', strtotime($Date. ' + '.$time.' '.$unit));
				
				$query_rsteam = $db->prepare("SELECT t.email AS email, title, fullname FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid WHERE userid = '$responsible'");
				$query_rsteam->execute();
				$row_rsteam = $query_rsteam->fetch();
				$totalRows_rsteam = $query_rsteam->rowCount();
				$title = $row_rsteam['title'];
				$fullname = $title.".".$row_rsteam['fullname'];
				$receipient = $row_rsteam['email'];
				
				$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
				$query_url->execute();		
				$row_url = $query_url->fetch();
				$url = $row_url["main_url"];
				$org = $row_url["company_name"];
				$org_email = $row_url["email_address"];
				
				$urlextention = "view-project-survey";
				if($outcomedataSource == 2){
					$urlextention = "evaluation-secondary-data-source";
				}
			
				$detailslink = '<a href="'.$url.$urlextention.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">CLICK HERE TO SEE DETAILS</a>';
				
				$mainmessage = ' Dear ' . $fullname . ',
				<p>Please note you have been assigned to carry out project baseline survey as per the details below:</p>
				<p>Project Code:' . $projcode . '<br>
				Project Name: ' . $projname . '<br>
				Expected due date: ' . $duedate . '</p>
				<p>Prepare the required resources. </p>';
				$title = "Project Baseline Survey";
				$subject = "Project Baseline Survey";
				$receipientName = $fullname;
				
				include("assets/processor/email-body.php");
				include("assets/processor/email-conf-settings.php");
				
                $msg = 'The M&E Plan has been successfully updated!';
                $results = "<script type=\"text/javascript\">
                            swal({
                            title: \"Success!\",
                            text: \" $msg\",
                            type: 'Success',
                            timer: 2000,
                            showConfirmButton: false });
                            setTimeout(function(){
                                    window.location.href = 'view-mne-plan';
                                }, 2000);
                        </script>";
            }
        }
    
?>
    <style>
        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
                height: 100%;
            }
        }
    </style>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?= $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <form id="addprogform" method="POST" name="addprogform" action="" onsubmit="return formVal()" enctype="multipart/form-data" autocomplete="off">
                                <fieldset class="scheduler-border">
                                    <div id="hidden_fields">
                                        <input type="hidden" name="progid" id="progid" value="<?= $progid ?>">
                                        <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    </div>
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i> Add Monitoring and Evaluation Details
                                    </legend>

                                    <div class="col-md-6">
                                        <label class="control-label">Responsible *:</label>
                                        <div class="form-line">
                                            <select name="responsible" id="responsible" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                <option value="">.... Select from list ....</option>
                                                <?php
                                                $query_responsible =  $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE directorate=42");
                                                $query_responsible->execute();
                                                $row_responsible = $query_responsible->fetch();
                                                do {
                                                    if ($responsible == $row_responsible['userid']) {
                                                ?>
                                                        <option value="<?php echo $row_responsible['userid'] ?>" selected><?php echo $row_responsible['fullname'] ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="<?php echo $row_responsible['userid'] ?>"><?php echo $row_responsible['fullname'] ?></option>
                                                <?php
                                                    }
                                                } while ($row_responsible = $query_responsible->fetch());
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Report User *:</label>
                                        <div class="form-line">
                                            <select name="reportUser[]" multiple id="reportUser" data-actions-box="true" class="form-control show-tick selectpicker" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                <?php
                                                $query_reportUser =  $db->prepare("SELECT * FROM tbl_pmdesignation ");
                                                $query_reportUser->execute();
                                                $row_reportUser = $query_reportUser->fetch();
                                                do {
                                                    if (in_array($row_reportUser['moid'], $report_user)) {
                                                ?>
                                                        <option value="<?php echo $row_reportUser['moid'] ?>" selected><?php echo $row_reportUser['designation'] ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="<?php echo $row_reportUser['moid'] ?>"><?php echo $row_reportUser['designation'] ?></option>
                                                <?php
                                                    }
                                                } while ($row_reportUser = $query_reportUser->fetch());
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">1. Add Outcome Details </legend>
                                        <div class="col-md-12">
                                            <label for="outcomeName" class="control-label">Outcome *:</label>
                                            <div class="form-input">
                                                <input type="text" name="outcome" id="outcome" value="<?php echo $outcome ?>" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-12 row">
                                            <div class="col-md-3">
                                                <label for="outcomeunitofmeasure" class="control-label">Unit of Measure <span id="impunit"></span>*:</label>
                                                <div class="form-input">
                                                    <input type="text" name="outcomeunitofmeasure" value="<?= $ocunitofmeasure ?>" readonly id="outcomeunitofmeasure" placeholder="Enter Impact Target" class="form-control" required="required">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="outcomeunitofmeasure" class="control-label"><span id="impunit">&nbsp;</span></label>
                                                <div class="form-input">
                                                    <div class="form-control" disabled>of</div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="outcomeName" class="control-label">Change to be measured *:</label>
                                                <div class="form-input">
                                                    <select name="outcomeIndicator" id="outcomeIndicator" onclick="get_outcome_details()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                        <option value="">.... Select from list ....</option>
                                                        <?php
                                                        $query_rsOutcomeIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='OUTCOME' AND indicator_type=2 AND active = '1' ORDER BY indid");
                                                        $query_rsOutcomeIndicators->execute();
                                                        $row_rsOutcomeIndicators = $query_rsOutcomeIndicators->fetch();
                                                        $totalRows_rsOutcomeIndicators = $query_rsOutcomeIndicators->rowCount();

                                                        do {
                                                            $selected = $row_rsOutcomeIndicators['indid'] == $indid  ? "selected" : "";
                                                        ?>
                                                            <option value="<?php echo $row_rsOutcomeIndicators['indid'] ?>" <?= $selected ?>><?php echo $row_rsOutcomeIndicators['indicator_name'] ?></option>
                                                        <?php
                                                        } while ($row_rsOutcomeIndicators = $query_rsOutcomeIndicators->fetch());
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <label for="outcom_calc_method" class="control-label">Calculation Method *:</label>
                                                <div class="form-input">
                                                    <input type="text" name="outcom_calc_method" value="<?= $occalc_method ?>" readonly id="outcom_calc_method" placeholder="Enter Indicator Calculation Method" class="form-control" required="required">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="control-label">&nbsp; </label>
                                                <div class="form-line">
                                                    &nbsp;
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                        <h4><u>Data collection Plan:</u></h4>
                                        <div class="col-md-4">
                                            <label for="outcomeName" class="control-label">Source of data *:</label>
                                            <div class="form-input">
                                                <?php
                                                $primary = "";
                                                $secondary = "";
                                                if ($Outcomedata_source == 1) {
                                                    $primary = "selected";
                                                } else {

                                                    $secondary = "selected";
                                                }
                                                ?>
                                                <select name="outcomedataSource" id="editoutcomedataSource" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                    <option value="">.... Select from list ....</option>
                                                    <option value="1" <?= $primary ?>>Primary</option>
                                                    <option value="2" <?= $secondary ?>>Secondary</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Timing (Years)*:</label>
                                            <div class="form-line">
                                                <input type="hidden" name="outcomeEvaluation" id="outcomeEvaluation">
                                                <input type="number" name="outcomeEvaluationFreq" onchange="outcome_Evaluation()" onkeyup="outcome_Evaluation()" value="<?= $Outcomeevaluation_frequency ?>" id="outcomeEvaluationFreq" class="form-control" placeholder="Enter Number of Year" required="required">
                                            </div>
                                        </div>
                                        <?php
                                        if ($Outcomedata_source == 1) {
                                        ?>
                                            <div class="col-md-12 editquestions">
                                                <label class="control-label">Key Questions</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th width="90%">Question</th>
                                                                <th width="5%">
                                                                    <button type="button" name="addplus" id="addplus" onclick="add_row_question();" class="btn btn-success btn-sm">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="questions_table_body">
                                                            <?php
                                                            $orowno = 0;
                                                            while ($row_outcomeevalqstns = $query_outcomeevalqstns->fetch()) {
                                                                $question = $row_outcomeevalqstns['question'];
                                                                $questionid = $row_outcomeevalqstns['id'];
																
                                                                $orowno++;
																?>
                                                                <tr id="questionrow<?= $orowno ?>">
                                                                    <td> <?= $orowno ?> </td>

                                                                    <td>
                                                                        <input type="text" name="questions[]" id="questions<?= $orowno ?>" value="<?= $question ?>" placeholder="Enter evaluation question" class="form-control querry" required />
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($orowno != 1) {
                                                                        ?>
                                                                            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_question("questionrow<?= $orowno ?>")'>
                                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                            </button>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <div class="col-md-12">
                                            <label class="control-label">Outcome Risks and Assumptions </label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="30%">Outcome Assumptions/Risks Category</th>
                                                            <th width="60%">Assumption/s</th>
                                                            <th width="5%">
                                                                <button type="button" name="addplus" id="addplus" onclick="add_row_outcome();" class="btn btn-success btn-sm">
                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="outcome_table_body">
                                                        <?php
                                                        $query_rsOutcomeRskDetails =  $db->prepare("SELECT * FROM tbl_projectrisks WHERE  projid='$projid' and  type=2  ");
                                                        $query_rsOutcomeRskDetails->execute();
                                                        $row_rsOutcomeRskDetails = $query_rsOutcomeRskDetails->fetch();
                                                        $orowno = 0;
                                                        do {
                                                            $outcomeRisks = $row_rsOutcomeRskDetails['rskid'];
                                                            $assumption  = $row_rsOutcomeRskDetails['assumption'];
                                                            $orowno++;
                                                        ?>
                                                            <tr id="outcomerow<?= $orowno ?>">
                                                                <td> <?= $orowno ?> </td>
                                                                <td>
                                                                    <select data-id="<?= $orowno ?>" name="outcomerisk[]" id="outcomeriskrow<?= $orowno ?>" class="form-control  selected_outcome" required="required">
                                                                        <?php
                                                                        $query_rsRisk =  $db->prepare("SELECT * FROM tbl_projrisk_categories");
                                                                        $query_rsRisk->execute();
                                                                        $row_rsRisk = $query_rsRisk->fetch();
                                                                        $totalRows_rsRisk = $query_rsRisk->rowCount();
                                                                        $input = '<option value="">... Select from list ...</option>';
                                                                        if ($totalRows_rsRisk > 0) {
                                                                            do {
                                                                                $type = explode(',', $row_rsRisk['type']);
                                                                                if (in_array(2, $type)) {
                                                                                    $selected = ($outcomeRisks  == $row_rsRisk['rskid']) ? "selected" : '';
                                                                                    $input .= '<option value="' . $row_rsRisk['rskid'] . '" ' . $selected . '>' . $row_rsRisk['category'] . ' </option>';
                                                                                }
                                                                            } while ($row_rsRisk = $query_rsRisk->fetch());
                                                                        } else {
                                                                            $input .= '<option value="">No Risks Found</option>';
                                                                        }
                                                                        echo $input;
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="outcome_assumptions[]" id="outcome_assumptions<?= $orowno ?>" value="<?= $assumption ?>" placeholder="Enter" class="form-control" required />
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($orowno != 1) {
                                                                    ?>
                                                                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_outcome("outcomerow<?= $orowno ?>")'>
                                                                            <span class="glyphicon glyphicon-minus"></span>
                                                                        </button>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        } while ($row_rsOutcomeRskDetails = $query_rsOutcomeRskDetails->fetch());
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2. Add Output Details </legend>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="3%">#</th>
                                                        <th width="47%">Output</th>
                                                        <th width="30%">Indicator</th>
                                                        <th width="15%">Add Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="">
                                                    <?php
                                                    //get project output details 
                                                    $query_OutputData = $db->prepare("SELECT * FROM  tbl_project_details  WHERE projid = '$projid' ORDER BY id ASC");
                                                    $query_OutputData->execute();
                                                    $countrows_OutpuData = $query_OutputData->rowCount();
                                                    $row_OutputData =  $query_OutputData->fetch();
                                                    $Ocounter = 0;

                                                    if ($countrows_OutpuData > 0) {
                                                        do {
                                                            $input = '';
                                                            $body = '';
                                                            $Ocounter++;
                                                            $year =  $row_OutputData['year'];
                                                            $projoutputID =  $row_OutputData['id'];
                                                            $projduration =  $row_OutputData['duration'];
                                                            $outputid =  $row_OutputData['outputid'];
                                                            $indicatorId =  $row_OutputData['indicator'];

                                                            //get output name 
                                                            $query_Output = $db->prepare("SELECT output, id FROM `tbl_progdetails`  WHERE id = '$outputid'");
                                                            $query_Output->execute();
                                                            $rows_Outputcount = $query_Output->rowCount();
                                                            $row_output =  $query_Output->fetch();
                                                            $outputname = $row_output ?  $row_output['output'] : "";

                                                            //get indicator name 
                                                            $query_dep = $db->prepare("SELECT * FROM  tbl_indicator  WHERE  indid ='$indicatorId' ");
                                                            $query_dep->execute();
                                                            $row = $query_dep->fetch();
                                                            $indname =  $row['indicator_name'];
                                                            $unit =  $row['indicator_unit'];
                                                            $calcid =  $row['indicator_calculation_method'];
                                                            $calcid =  $row['indicator_disaggregation'];

                                                            $query_Indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
                                                            $query_Indicator_cal->execute(array(':calcid' => $calcid));
                                                            $row_cal = $query_Indicator_cal->fetch();
                                                            $op_calc_method = $row_cal ? $row_cal['method'] : "";

                                                            $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
                                                            $query_Indicator->execute(array(":unit" => $unit));
                                                            $row = $query_Indicator->fetch();
                                                            $op_unitofmeasure = $row['unit'];
                                                    ?>
                                                            <tr>
                                                                <td><?= $Ocounter ?></td>
                                                                <td>
                                                                    <?= $outputname ?>
                                                                </td>
                                                                <td>
                                                                    <?= $indname ?>
                                                                    <input type="hidden" name="outputid[]" id="outputid<?= $projoutputID ?>" value="<?= $projoutputID ?>" />
                                                                    <input type="hidden" name="ben_diss[]" id="ben_diss<?= $projoutputID ?>" value="<?= $dissagragated ?>" />
                                                                    <input type="hidden" name="outputName[]" id="outputName<?= $projoutputID ?>" value="<?= $outputname ?>" />
                                                                    <input type="hidden" name="indicatorid[]" id="indicatorid<?= $projoutputID ?>" value="<?= $indicatorId ?>" />
                                                                    <input type="hidden" name="indicatorName[]" id="indicatorName<?= $projoutputID ?>" value="<?= $indname ?>" />
                                                                    <input type="hidden" name="output_details_unitof_measure[]" id="output_details_unitof_measure<?= $projoutputID ?>" value="<?= $op_unitofmeasure ?>" />
                                                                    <input type="hidden" name="output_calculation_method[]" id="output_calculation_method<?= $projoutputID ?>" value="<?= $op_calc_method ?>" />
                                                                    <input type="hidden" name="output_details_id[]" id="output_details_id<?= $projoutputID ?>" value="true" />
                                                                    <input type="hidden" name="projid" id="projidid" value="<?= $projid ?>" />
                                                                </td>
                                                                <td>
                                                                    <a type=" button" data-toggle="modal" data-target="#outputItemModal" onclick='getopdetails(<?= $projoutputID ?>)' id="outputItemModalBtn<?= $projoutputID ?>"> Edit Details</a>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                        } while ($row_OutputData =  $query_OutputData->fetch());
                                                    } else {
                                                        echo '<tr><td colspan="4">No Output Found</td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="col-md-12" style="margin-top:15px" align="center">
                                        <input type="hidden" name="MM_insert" value="addmefrm">
                                        <button class="btn btn-success" type="submit">Update</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
    <!-- Start Modal Item Edit -->
    <div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center" id="modal-title"><i class="fa fa-pencil"></i> Edit Output Plan</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="outputform" action="" method="POST">
                                            <br />
                                            <div class="col-md-12">
                                                <label for="outputName" class="control-label">Output *:</label>
                                                <div class="form-input">
                                                    <input type="text" name="outputName" id="outputsName" value="" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="col-md-3">
                                                    <label for="outputunitofmeasure" class="control-label">Units of Measure *:</label>
                                                    <div class="form-input">
                                                        <input type="text" name="outputunitofmeasure" value="<?= $unitofmeasure ?>" readonly id="outputunitofmeasure" placeholder="Enter Impact Target" class="form-control" required="required">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label for="outcomeunitofmeasure" class="control-label"><span id="impunit">&nbsp;</span></label>
                                                    <div class="form-input">
                                                        <div class="form-control" disabled>of</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="outputIndicator" class="control-label">Result to be measured *:</label>
                                                    <div class="form-line">
                                                        <input type="text" name="outputIndicator" id="outputIndicator" value="" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Monitoring Frequency *:</label>
                                                <div class="form-line">
                                                    <select name="outputMonitorigFreq" id="outputMonitorigFreq" onchange="get_reporting_timeline()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                        <option value="">.... Select from list ....</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Output Disaggregation </label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" id="" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th width="50%">Location </th>
                                                                <th width="45%">Responsible </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="op_table_body">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Output Risks and Assumptions</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" id="output_table" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th width="30%">Output Risks</th>
                                                                <th width="60%">Assumption/s</th>
                                                                <th width="5%">
                                                                    <button type="button" name="addplus" id="addplus" onclick="add_row_output();" class="btn btn-success btn-sm">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="output_table_body">
                                                            <?php
                                                            $query_rsOutcomeRskDetails = $db->prepare("SELECT * FROM tbl_projectrisks WHERE projid='$projid' and type=3");
                                                            $query_rsOutcomeRskDetails->execute();
                                                            
                                                            $orowno = 0;
                                                            while($row_rsOutcomeRskDetails = $query_rsOutcomeRskDetails->fetch()) {
                                                                $outcomeRisks = $row_rsOutcomeRskDetails['rskid'];
                                                                $assumption  = $row_rsOutcomeRskDetails['assumption'];
                                                                $orowno++;
																?>
                                                                <tr id="outcomerow<?= $orowno ?>">
                                                                    <td> <?= $orowno ?> </td>
                                                                    <td>
                                                                        <select data-id="<?= $orowno ?>" name="outputrisk[]" id="outputrisk<?= $orowno ?>" class="form-control  selected_output" required="required">
                                                                            <?php
                                                                            $query_rsRisk = $db->prepare("SELECT * FROM tbl_projrisk_categories");
                                                                            $query_rsRisk->execute();
                                                                            $totalRows_rsRisk = $query_rsRisk->rowCount();
                                                                            $input = '<option value="">... Select from list ...</option>';
                                                                            if ($totalRows_rsRisk > 0) {
                                                                                while ($row_rsRisk = $query_rsRisk->fetch()) {
                                                                                    $type = explode(',', $row_rsRisk['type']);
                                                                                    if (in_array(3, $type)) {
                                                                                        $selected = ($outcomeRisks == $row_rsRisk['rskid']) ? "selected" : '';
                                                                                        $input .= '<option value="' . $row_rsRisk['rskid'] . '" ' . $selected . '>' . $row_rsRisk['category'] . ' </option>';
                                                                                    }
                                                                                } 
                                                                            } else {
                                                                                $input .= '<option value="">No Risks Found</option>';
                                                                            }
                                                                            echo $input;
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="output_assumptions[]" id="output_assumptions<?= $orowno ?>" value="<?= $assumption ?>" placeholder="Enter" class="form-control" required />
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($orowno != 1) {
                                                                        ?>
                                                                            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_outcome("outcomerow<?= $orowno ?>")'>
                                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                            </button>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="col-md-12 text-center">
                                                    <input type="hidden" name="addoutput" id="addoutput" value="addoutput">
                                                    <input type="hidden" name="opid" id="opid" value="">
                                                    <input type="hidden" name="output_indicator" id="output_indicator" value="">
                                                    <input type="hidden" name="dpid" id="dpid" value="">
                                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
                                                    <input type="hidden" name="user_name" id="user_name" value="<?= 4 ?>">
                                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
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
<?php
} else {
    $results =  restriction();
    echo $results;
}
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
require('includes/footer.php');
?>
<script src="assets/custom js/add-project-mne-plan.js"></script>