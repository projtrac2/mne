<?php
require('includes/head.php');
if ($permission) {
    try {
        $directorate_responsible = 42;
        $implimentation_stage = 10;
        function get_members($member_id)
        {
            global $db, $directorate_responsible;
            $query_team = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE directorate = :directorate");
            $query_team->execute(array(":directorate" => $directorate_responsible));
            $row_team = $query_team->fetch();
            $input = '<option value="">Select Member</option>';
            if ($row_team) {
                do {
                    $user_id = $row_team['userid'];
                    $title_id = $row_team['title'];
                    $firstname = $row_team['firstname'];
                    $middlename = $row_team['middlename'];
                    $lastname = $row_team['lastname'];
                    $query_rsTitle = $db->prepare("SELECT * FROM `tbl_titles` WHERE id=:title_id ");
                    $query_rsTitle->execute(array(":title_id" => $title_id));
                    $row_rsTitle = $query_rsTitle->fetch();
                    $title = $row_rsTitle ? $row_rsTitle['title'] : '';
                    $membername = $title . ". " . $firstname . " " . $middlename . " " . $lastname;
                    $selected = $user_id == $member_id ? 'selected' : '';
                    $input .= '<option value="' . $user_id . '" ' . $selected . '>' . $membername . '</option>';
                } while ($row_team = $query_team->fetch());
            }
            return $input;
        }

        function get_roles($role_id)
        {
            global  $projevaluation, $project_impact;
            $selected = $role_id == 1 ? "selected" : '';
            $role_input = '
            <option value="">Select Result Level</option> ';
            if ($projevaluation != 0) {
                $selected = $role_id == 2 ? "selected" : '';
                $role_input .= '<option value="2" ' . $selected . '>Outcome</option>';
                if ($project_impact != 0) {
                    $selected = $role_id == 3 ? "selected" : '';
                    $role_input .= '<option value="3" ' . $selected . '>Impact</option>';
                }
            }

            return $role_input;
        }

        function get_measurement($unit)
        {
            global $db;
            $sql = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
            $sql->execute(array(":unit_id" => $unit));
            $row = $sql->fetch();
            $rows_count = $sql->rowCount();
            return ($rows_count > 0) ?   $row['unit'] : "";
        }

        $decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : header("Location: view-mne-plan.php");
        $projid_array = explode("projid04", $decode_projid);
        $projid = $projid_array[1];

        $results = "";
        $datecreated  = date("Y-m-d");

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $progid = $row_rsProjects['progid'];
        $project = $row_rsProjects['projname'];
        $sectorid = $row_rsProjects['projsector'];
        $projevaluation = $row_rsProjects['projevaluation'];
        $project_impact = $row_rsProjects['projimpact'];
        $deptid = $row_rsProjects['projdept'];
        $projduration = $row_rsProjects['projduration'];
        $monitoring_frequency = $row_rsProjects['monitoring_frequency'];
        $activity_monitoring_frequency = $row_rsProjects['activity_monitoring_frequency'];
        $projfscyear = $row_rsProjects['projfscyear'];
        $mne_budget = $row_rsProjects['mne_budget'];
        $project_sub_stage = $row_rsProjects['proj_substage'];
        $project_directorate = $row_rsProjects['directorate'];
        $workflow_stage = $row_rsProjects['projstage'];
        $approval_stage = ($project_sub_stage  >= 2) ? true : false;


        $query_rsYear =  $db->prepare("SELECT * FROM tbl_fiscal_year where id ='$projfscyear'");
        $query_rsYear->execute();
        $row_rsYear = $query_rsYear->fetch();

        $starting_year = $row_rsYear ? $row_rsYear['yr'] : false;
        $s_date = $starting_year . "-07-01";
        $project_end_date = date('Y-m-d', strtotime($s_date . ' + ' . $projduration . ' days'));
        $end_month = date('m', strtotime($project_end_date));
        $end_year_c = date('Y', strtotime($project_end_date));
        $endyear = $end_month >= 7 && $end_month <= 12 ? $end_year_c : $end_year_c - 1;
        $years = ($endyear - $starting_year) + 1;

        //=============================================== IMPACT SECTION ==============================================================================

        $query_impact_details = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE projid=:projid");
        $query_impact_details->execute(array(":projid" => $projid));
        $totalRows_impact_details = $query_impact_details->rowCount();

        $query_impactIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Impact' AND indicator_type=2 AND active = '1' AND indicator_dept = '$deptid' ORDER BY indid");
        $query_impactIndicators->execute();
        $row_impactIndicators = $query_impactIndicators->fetch();
        $totalRows_impactIndicators = $query_impactIndicators->rowCount();

        $projectimpact = $impactindicator = $impactunit = $projimpactindicator = $impact_calc_method = $impactSourceid = $impact_evaluation_frequency = $impact_evaluation_number = $impact_responsible = "";

        $query_impact_ind = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='OUTCOME' AND indicator_type=2 AND active = '1' AND indicator_dept = '$deptid' ORDER BY indid");
        $query_impact_ind->execute();
        $totalRows_impact_ind = $query_impact_ind->rowCount();

        //=============================================== OUTCOME SECTION ==============================================================================

        $query_outcome_details = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid=:projid");
        $query_outcome_details->execute(array(":projid" => $projid));
        $totalRows_outcome_details = $query_outcome_details->rowCount();

        //main  evaluation questions
        $query_outcomemainevalqstns =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid='$projid' AND resultstype=2 AND questiontype=1");
        $query_outcomemainevalqstns->execute();
        $row_outcomemainevalqstns = $query_outcomemainevalqstns->fetch();
        $count_outcomemainevalqstns = $query_outcomemainevalqstns->rowCount();

        //Outcome  evaluation questions
        $query_outcomeevalqstns =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid='$projid' AND resultstype=2 AND questiontype=2");
        $query_outcomeevalqstns->execute();
        $row_outcomeevalqstns = $query_outcomeevalqstns->fetch();
        $count_outcomeevalqstns = $query_outcomeevalqstns->rowCount();

        $query_rsOutcomeIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Outcome' AND indicator_type=2 AND indicator_dept = '$deptid' AND active = '1' ORDER BY indid");
        $query_rsOutcomeIndicators->execute();
        $totalRows_rsOutcomeIndicators = $query_rsOutcomeIndicators->rowCount();

        $query_parent_outcome = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid='$projid' ORDER BY id");
        $query_parent_outcome->execute();
        $totalRows_parent_outcome = $query_parent_outcome->rowCount();

        $query_parent_impact = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE projid='$projid' ORDER BY id");
        $query_parent_impact->execute();
        $totalRows_parent_impact = $query_parent_impact->rowCount();

        $projoutcome =  $ocindicator = $ocunit = $projoutcomeindicator = $outcom_calc_method = $OutComeSourceid = $evaluation_frequency = "";

        $impactactive = 0;
        $outcomeactive = 0;
        $impacthead = "";
        $outcomehead = "";
        $outputhead = "";
        $impactbody = "";
        $outcomebody = "";
        $outputbody = "";
        if ($projevaluation != 0 && $project_impact != 0) {
            $impactactive = 1;
            $outcomeactive = 1;
        } elseif ($projevaluation != 0 && $project_impact == 0) {
            $outcomeactive = 1;
        }

        $query_Mapping = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid AND indicator_mapping_type <> 0 ORDER BY id ASC");
        $query_Mapping->execute(array(":projid" => $projid));
        $countrows_Mapping = $query_Mapping->rowCount();
        $mappingactivite = $countrows_Mapping > 0 ? true : false;

        if (isset($_POST['store_project_details'])) {
            $projid = $_POST['projid'];
            $monitoring_frequency = $_POST['monitoring_frequency'];
            $activity_monitoring_frequency = $_POST['activity_monitoring_frequency'];
            $datecreated = date("Y-m-d");
            $createdby = $_POST['user_name'];
            $mnecode = "AB123" . $projid;

            $sql = $db->prepare("UPDATE `tbl_projects` SET monitoring_frequency=:monitoring_frequency, activity_monitoring_frequency=:activity_monitoring_frequency WHERE projid=:projid ");
            $result = $sql->execute(array(':monitoring_frequency' => $monitoring_frequency, ":activity_monitoring_frequency" => $activity_monitoring_frequency, ":projid" => $projid));

            $hash = base64_encode("projid04{$projid}");
            $redirect_url = "add-project-mne-plan.php?proj=$hash";

            $results = "
                <script type='text/javascript'>
                    swal({
                    title: 'Success!',
                    text: 'Payment plan successfully created',
                    type: 'Success',
                    timer: 2000,
                    icon:'success',
                    showConfirmButton: false });
                    setTimeout(function(){
                        window.location.href = '$redirect_url';
                    }, 2000);
                </script>";
            echo $results;
        }

        if (isset($_POST['store_project_team'])) {
            $projid = $_POST['projid'];
            $datecreated = date("Y-m-d");
            $createdby = $_POST['user_name'];
            $mnecode = "AB123" . $projid;
            $members = $_POST['member'];
            $roles = $_POST['role'];

            $sql = $db->prepare("DELETE FROM `tbl_projmembers` WHERE projid=:projid AND team_type <= 3");
            $result = $sql->execute(array(':projid' => $projid));


            $total_members = count($members);
            for ($i = 0; $i < $total_members; $i++) {
                $role = $roles[$i];
                $ptid = $members[$i];
                $implimentation_stage =  $role == 1 ? 10 : 9;
                $insertSQL = $db->prepare("INSERT INTO tbl_projmembers (projid,role,stage,team_type,responsible,created_by,created_at) VALUES (:projid,:role,:stage,:team_type,:responsible,:created_by,:created_at)");
                $result = $insertSQL->execute(array(':projid' => $projid, ':role' => $role, ":stage" => $implimentation_stage, ':team_type' => $role, ':responsible' => $ptid, ':created_by' => $user_name, ':created_at' => $today));
            }

            $hash = base64_encode("projid04{$projid}");
            $redirect_url = "add-project-mne-plan.php?proj=$hash";
            $results = "
                <script type='text/javascript'>
                    swal({
                    title: 'Success!',
                    text: 'Team added successfully created',
                    type: 'Success',
                    timer: 2000,
                    icon:'success',
                    showConfirmButton: false });
                    setTimeout(function(){
                        window.location.href = '$redirect_url';
                    }, 2000);
                </script>";
            echo $results;
        }

        function validate_mne()
        {
            global $db, $projid, $projevaluation, $mne_budget, $project_impact, $monitoring_frequency;
            if ($projevaluation != 0) {
                $query_outcome_details = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid=:projid");
                $query_outcome_details->execute(array(":projid" => $projid));
                $totalRows_outcome_details = $query_outcome_details->rowCount();
                $result[] = $totalRows_outcome_details > 0 ? true : false;
                $query_rsTeam =  $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid AND stage=:stage AND team_type = 2 ");
                $query_rsTeam->execute(array(":projid" => $projid));
                $totalRows_rsTeam = $query_rsTeam->rowCount();
                $result[] = $totalRows_rsTeam > 0 ? true : false;

                if ($project_impact != 0) {
                    $query_impact_details = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE projid=:projid");
                    $query_impact_details->execute(array(":projid" => $projid));
                    $totalRows_impact_details = $query_impact_details->rowCount();
                    $result[] = $totalRows_impact_details > 0 ? true : false;

                    $query_rsTeam =  $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid AND team_type = 3 ");
                    $query_rsTeam->execute(array(":projid" => $projid));
                    $totalRows_rsTeam = $query_rsTeam->rowCount();
                    $result[] = $totalRows_rsTeam > 0 ? true : false;
                }
            }

            $query_rsTask_direct = $db->prepare("SELECT SUM(units_no * unit_cost) as total_cost FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type > 2");
            $query_rsTask_direct->execute(array(':projid' => $projid));
            $row_rsTask_direct = $query_rsTask_direct->fetch();
            $sum_cost = $row_rsTask_direct['total_cost'] != null ? $row_rsTask_direct['total_cost'] : "0";

            $result[] = $mne_budget == $sum_cost ? true : false;
            $result[] = $monitoring_frequency != '' ? true : false;
            return !in_array(false, $result) ? true : false;
        }
    } catch (PDOException $ex) {
        $result = "An error occurred: " . $ex->getMessage();
        print($result);
    }
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <a type="button" id="outputItemModalBtnrow" href="view-mne-plan.php" class="btn btn-warning pull-right">
                                Go Back
                            </a>
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
                        <div class="card-header">
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#output"><i class="fa fa-list bg-blue" aria-hidden="true"></i> Output/s&nbsp;<span class="badge bg-blue">|</span></a>
                                </li>
                                <?php if ($outcomeactive) { ?>
                                    <li class="<?= $outcomehead ?>">
                                        <a data-toggle="tab" href="#outcome"><i class="fa fa-tasks bg-indigo" aria-hidden="true"></i> Outcome/s&nbsp;<span class="badge bg-indigo">|</span></a>
                                    </li>
                                <?php }
                                if ($impactactive) { ?>
                                    <li class="<?= $impacthead ?>">
                                        <a data-toggle="tab" href="#impact"><i class="fa fa-id-card-o bg-green" aria-hidden="true"></i> Impact/s &nbsp;<span class="badge bg-green">|</span></a>
                                    </li>
                                <?php } ?>
                                <li>
                                    <a data-toggle="tab" href="#team"><i class="fa fa-users bg-lime" aria-hidden="true"></i> M&E Team &nbsp;<span class="badge bg-lime">|</span></a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#budget"><i class="fa fa-money bg-orange" aria-hidden="true"></i> M&E Budget &nbsp;<span class="badge bg-orange">|</span></a>
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="myprojid" id="myprojid" value="<?= $projid ?>">
                        <div class="body">
                            <div class="table-responsive">
                                <div class="tab-content">
                                    <div id="output" class="tab-pane fade in active">
                                        <fieldset class="scheduler-border" style="border-radius:3px">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-list" style="color:#F44336" aria-hidden="true"></i> Output Details
                                            </legend>
                                            <div class="body">
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <h4><u>Project: <?= $project ?></u></h4>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover">
                                                                <thead class="bg-grey">
                                                                    <tr>
                                                                        <th style="width:5%">#</th>
                                                                        <th style="width:95%">Output</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $query_OutputData = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator  WHERE projid = :projid ORDER BY id ASC");
                                                                    $query_OutputData->execute(array(":projid" => $projid));
                                                                    $countrows_OutpuData = $query_OutputData->rowCount();
                                                                    if ($countrows_OutpuData > 0) {
                                                                        $counter = 0;
                                                                        while ($row_rsOutput =  $query_OutputData->fetch()) {
                                                                            $output = $row_rsOutput['indicator_name'];
                                                                            $counter++;
                                                                    ?>
                                                                            <tr id="guarantee_row">
                                                                                <td style="width:5%"><?= $counter ?></td>
                                                                                <td style="width:95%"><?= $output ?></td>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <form class="form-horizontal" id="outputform" action="" method="POST">
                                                            <br />
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="control-label">Activity Target breakdown Frequency *: </label>
                                                                <div class="form-line">
                                                                    <select name="activity_monitoring_frequency" id="" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                                        <option value="">.... Select from list ....</option>
                                                                        <?php
                                                                        $query_frequency = $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE status=1 ");
                                                                        $query_frequency->execute();
                                                                        $totalRows_frequency = $query_frequency->rowCount();
                                                                        $input = '';
                                                                        if ($totalRows_frequency > 0) {
                                                                            while ($row_frequency = $query_frequency->fetch()) {
                                                                                $selected =  $row_frequency['fqid'] == $activity_monitoring_frequency ? 'selected' : '';
                                                                                $input .= '<option value="' . $row_frequency['fqid'] . '" ' . $selected . ' >' . $row_frequency['frequency'] . ' </option>';
                                                                            }
                                                                        }
                                                                        echo $input;
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="control-label">Monitoring Frequency *: </label>
                                                                <div class="form-line">
                                                                    <select name="monitoring_frequency" id="" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                                        <option value="">.... Select from list ....</option>
                                                                        <?php
                                                                        $query_frequency = $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE status=1 ");
                                                                        $query_frequency->execute();
                                                                        $totalRows_frequency = $query_frequency->rowCount();
                                                                        $input = '';
                                                                        if ($totalRows_frequency > 0) {
                                                                            while ($row_frequency = $query_frequency->fetch()) {
                                                                                $selected =  $row_frequency['fqid'] == $monitoring_frequency ? 'selected' : '';
                                                                                $input .= '<option value="' . $row_frequency['fqid'] . '" ' . $selected . ' >' . $row_frequency['frequency'] . ' </option>';
                                                                            }
                                                                        }
                                                                        echo $input;
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                                <input type="hidden" name="store_project_details" id="store_project_details" value="store_project_details">
                                                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
                                                                <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="project_submit" value="<?= $monitoring_frequency == "" ? "Save" : "Update" ?>" />
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <?php if ($outcomeactive) { ?>
                                        <div id="outcome" class="tab-pane fade <?= $outcomebody ?>">
                                            <fieldset class="scheduler-border" style="border-radius:3px">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                    <i class="fa fa-tasks" style="color:#F44336" aria-hidden="true"></i> Outcome Details
                                                </legend>
                                                <div class="header row">
                                                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                                        <button type="button" id="modal_button" class="pull-right btn bg-indigo" data-toggle="modal" id="addOutcomeModalBtn" data-target="#addOutcomeModal" style="margin-top:-10px; margin-bottom:-10px"> <i class="fa fa-plus-square"></i> Add Outcome </button>
                                                    </div>
                                                </div>
                                                <div class="body">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover" id="manageOutcomeTable" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="5%">#</th>
                                                                        <th width="40%">Outcome</th>
                                                                        <th width="15%">Source of Data</th>
                                                                        <th width="18%">Evaluation Frequency</th>
                                                                        <th width="15%">Number of Endline Evaluations</th>
                                                                        <th width="7%">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    <?php }
                                    if ($impactactive) { ?>
                                        <div id="impact" class="tab-pane fade <?= $impactbody ?>">
                                            <fieldset class="scheduler-border" style="border-radius:3px">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                    <i class="fa fa-id-card-o" style="color:#F44336" aria-hidden="true"></i> Impact Details
                                                </legend>
                                                <div class="header row">
                                                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                                        <button type="button" id="modal_button" class="pull-right btn bg-green" data-toggle="modal" id="addImpactModalBtn" data-target="#addImpactModal" style="margin-top:-10px; margin-bottom:-10px"> <i class="fa fa-plus-square"></i> Add Impact</button>
                                                    </div>
                                                </div>
                                                <div class="body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover" id="manageImpactTable" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%">#</th>
                                                                    <th width="40%">Impact</th>
                                                                    <th width="15%">Source of Data</th>
                                                                    <th width="18%">Evaluation Frequency</th>
                                                                    <th width="15%">Number of Endline Evaluations</th>
                                                                    <th width="7%">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    <?php } ?>
                                    <div id="team" class="tab-pane ">
                                        <fieldset class="scheduler-border" style="border-radius:3px">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-users" style="color:#F44336" aria-hidden="true"></i> M&E Team
                                            </legend>
                                            <div class="body">
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <form class="form-horizontal" id="add_team_form" action="" method="POST">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered" id="members_table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width:5%;">#</th>
                                                                                <th style="width:40%">Result Level *:</th>
                                                                                <th style="width:45%">Member *:</th>
                                                                                <th style="width:5%">
                                                                                    <button type="button" name="addplus" id="add_row_gen" onclick="add_member()" class="btn btn-success btn-sm">
                                                                                        <span class="glyphicon glyphicon-plus">
                                                                                        </span>
                                                                                    </button>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="members_table_body">
                                                                            <?php
                                                                            $query_rsProject_Members =  $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND team_type <= 3");
                                                                            $query_rsProject_Members->execute(array(":projid" => $projid));
                                                                            $totalRows_rsProject_Members = $query_rsProject_Members->rowCount();
                                                                            if ($totalRows_rsProject_Members > 0) {
                                                                                $counter = 0;
                                                                                while ($row_rsProject_Members = $query_rsProject_Members->fetch()) {
                                                                                    $counter++;
                                                                                    $responsible = $row_rsProject_Members['responsible'];
                                                                                    $role = $row_rsProject_Members['role'];
                                                                            ?>
                                                                                    <tr id="memrow<?= $counter ?>">
                                                                                        <td><?= $counter ?></td>
                                                                                        <td>
                                                                                            <select name="role[]" id="rolesrow001" class="form-control" required="required">
                                                                                                <?= get_roles($role) ?>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td>
                                                                                            <select name="member[]" id="membersrow001" class="form-control members" required="required">
                                                                                                <?= get_members($responsible) ?>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php
                                                                                            if ($counter != 1) {
                                                                                            ?>
                                                                                                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_member("memrow<?= $counter ?>")'>
                                                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                                                </button>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <tr id="finrow001">
                                                                                    <td>1</td>
                                                                                    <td>
                                                                                        <select name="role[]" id="rolesrow001" class="form-control" required="required">
                                                                                            <?= get_roles(0) ?>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <select name="member[]" id="membersrow001" class="form-control members" required="required">
                                                                                            <?= get_members(0) ?>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                                <input type="hidden" name="store_project_team" id="store_project_team" value="store_project_team">
                                                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
                                                                <input type="hidden" name="projevaluation" id="projevaluation" value="<?= $projevaluation ?>" />
                                                                <input type="hidden" name="project_impact" id="project_impact" value="<?= $project_impact ?>" />
                                                                <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="project_submit" value="<?= $totalRows_rsProject_Members  == 0 ? "Save" : "Update" ?>" />
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div id="budget" class="tab-pane fade">
                                        <fieldset class="scheduler-border" style="border-radius:3px">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-money" style="color:#F44336" aria-hidden="true"></i> M&E Budget (Ksh. <?= number_format($mne_budget, 2) ?>)
                                            </legend>
                                            <div class="card-header">
                                                <ul class="nav nav-tabs" style="font-size:14px">
                                                    <li class="active">
                                                        <a data-toggle="tab" href="#monitoring"><i class="fa fa-list bg-deep-orange" aria-hidden="true"></i> Monitoring&nbsp;<span class="badge bg-deep-orange"></span></a>
                                                    </li>
                                                    <?php
                                                    if ($mappingactivite) {
                                                    ?>
                                                        <li>
                                                            <a data-toggle="tab" href="#mapping"><i class="fa fa-map bg-deep-orange" aria-hidden="true"></i> Mapping &nbsp;<span class="badge bg-deep-orange"></span></a>
                                                        </li>
                                                    <?php
                                                    }
                                                    if ($outcomeactive) {
                                                    ?>
                                                        <li>
                                                            <a data-toggle="tab" href="#evaluation"><i class="fa fa-book  bg-deep-orange" aria-hidden="true"></i> Evaluation Cost&nbsp;<span class="badge  bg-deep-orange"></span></a>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <div class="body tab-content">
                                                <div id="monitoring" class="tab-pane fade in active">
                                                    <?php
                                                    $cost_type = 4;
                                                    $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type ");
                                                    $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
                                                    $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
                                                    $edit = $totalRows_rs_output_cost_plan > 0 ? 1 : 0;

                                                    $query_rsDirect_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type ");
                                                    $query_rsDirect_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
                                                    $row_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->fetch();
                                                    $totalRows_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->rowCount();
                                                    $sum_cost = $totalRows_rs_output_cost_plan > 0 ? $row_rsDirect_cost_plan_budget['sum_cost'] : 0;
                                                    $budget_line = "Monitoring";
                                                    $budget_line_details =
                                                        "{
														cost_type : $cost_type,
														output_id: 0,
														budget_line_id: 0,
														budget_line: '$budget_line',
														plan_id: 0,
														task_id:0,
														edit:$edit,
														sum_cost:$sum_cost
													}";
                                                    ?>
                                                    <div class="card-header">
                                                        <div class="row clearfix" style="margin-top:10px">
                                                            <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
                                                                <h4><u>Monitoring Cost: <?= number_format($sum_cost, 2) ?> </u></h4>
                                                            </div>
                                                            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                                                                <button type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" onclick="add_budgetline(<?= $budget_line_details ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                    <?php echo $edit == 1 ? '<span class="glyphicon glyphicon-pencil"></span> ' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="hidden" name="budgetlineid<?= $cost_type ?>[]" id="budgetlineid<?= $cost_type ?>" value="<?= $sum_cost ?>" class="task_costs">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th># </th>
                                                                        <th>Description </th>
                                                                        <th>Unit</th>
                                                                        <th>Unit Cost</th>
                                                                        <th>No. of Units</th>
                                                                        <th>Total Cost</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="budget_lines_tableA">

                                                                    <?php
                                                                    if ($totalRows_rs_output_cost_plan > 0) {
                                                                        $table_counter = 0;
                                                                        while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
                                                                            $table_counter++;
                                                                            $unit = $row_rsOther_cost_plan['unit'];
                                                                            $unit_cost = $row_rsOther_cost_plan['unit_cost'];
                                                                            $units_no = $row_rsOther_cost_plan['units_no'];
                                                                            $rmkid = $row_rsOther_cost_plan['id'];
                                                                            $description = $row_rsOther_cost_plan['description'];
                                                                            $financial_year = $row_rsOther_cost_plan['financial_year'];
                                                                            $end_year = $financial_year + 1;
                                                                            $total_cost = $unit_cost * $units_no;
                                                                            $unit_of_measure =  get_measurement($unit);
                                                                    ?>
                                                                            <tr id="row">
                                                                                <td> <?= $table_counter ?></td>
                                                                                <td> <?= $description ?></td>
                                                                                <td> <?= $unit_of_measure ?></td>
                                                                                <td> <?= number_format($unit_cost, 2) ?></td>
                                                                                <td> <?= number_format($units_no) ?></td>
                                                                                <td> <?= number_format($total_cost, 2) ?></td>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                if ($mappingactivite) {
                                                ?>

                                                    <div id="mapping" class="tab-pane fade ">
                                                        <?php
                                                        $cost_type = 3;
                                                        $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type ");
                                                        $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
                                                        $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
                                                        $edit = $totalRows_rs_output_cost_plan > 0 ? 1 : 0;

                                                        $query_rsDirect_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type ");
                                                        $query_rsDirect_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
                                                        $totalRows_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->rowCount();
                                                        $row_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->fetch();
                                                        $sum_cost = $row_rsDirect_cost_plan_budget['sum_cost'] ? $row_rsDirect_cost_plan_budget['sum_cost'] : 0;
                                                        $budget_line = "Mapping";
                                                        $budget_line_details =
                                                            "{
															cost_type : $cost_type,
															output_id: 0,
															budget_line_id: 0,
															budget_line: '$budget_line',
															plan_id: 0,
															task_id:0,
															edit:$edit,
															sum_cost:$sum_cost
														}";
                                                        ?>
                                                        <div class="card-header">
                                                            <div class="row clearfix" style="margin-top:10px">
                                                                <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
                                                                    <h4><u>Mapping Cost: <?= number_format($sum_cost, 2) ?> </u></h4>
                                                                </div>
                                                                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                                                                    <button type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" onclick="add_budgetline(<?= $budget_line_details ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                        <?php echo $edit == 1 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="hidden" name="budgetlineid<?= $cost_type ?>[]" id="budgetlineid<?= $cost_type ?>" value="<?= $sum_cost ?>" class="task_costs">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th># </th>
                                                                            <th>Description </th>
                                                                            <th>Unit</th>
                                                                            <th>Unit Cost</th>
                                                                            <th>No. of Units</th>
                                                                            <th>Total Cost</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="budget_lines_tableB">
                                                                        <?php
                                                                        if ($totalRows_rs_output_cost_plan > 0) {
                                                                            $table_counter = 0;
                                                                            while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
                                                                                $table_counter++;
                                                                                $unit = $row_rsOther_cost_plan['unit'];
                                                                                $unit_cost = $row_rsOther_cost_plan['unit_cost'];
                                                                                $units_no = $row_rsOther_cost_plan['units_no'];
                                                                                $rmkid = $row_rsOther_cost_plan['id'];
                                                                                $description = $row_rsOther_cost_plan['description'];
                                                                                $financial_year = $row_rsOther_cost_plan['financial_year'];
                                                                                $total_cost = $unit_cost * $units_no;
                                                                                $unit_of_measure =  get_measurement($unit);
                                                                        ?>
                                                                                <tr id="row">
                                                                                    <td> <?= $table_counter ?></td>
                                                                                    <td> <?= $description ?></td>
                                                                                    <td> <?= $unit_of_measure ?></td>
                                                                                    <td> <?= number_format($unit_cost, 2) ?></td>
                                                                                    <td> <?= number_format($units_no) ?></td>
                                                                                    <td> <?= number_format($total_cost, 2) ?></td>
                                                                                </tr>
                                                                        <?php

                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                if ($outcomeactive) {
                                                ?>
                                                    <div id="evaluation" class="tab-pane fade">
                                                        <?php
                                                        $cost_type = 5;
                                                        $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type ");
                                                        $query_rs_output_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
                                                        $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
                                                        $edit = $totalRows_rs_output_cost_plan > 0 ? 1 : 0;

                                                        $query_rsDirect_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type ");
                                                        $query_rsDirect_cost_plan_budget->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
                                                        $row_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->fetch();
                                                        $totalRows_rsDirect_cost_plan_budget = $query_rsDirect_cost_plan_budget->rowCount();
                                                        $sum_cost = $row_rsDirect_cost_plan_budget['sum_cost'] != null ? $row_rsDirect_cost_plan_budget['sum_cost'] : 0;
                                                        $budget_line = "Outcome Baseline Evaluation";

                                                        $budget_line_details =
                                                            "{
																cost_type : $cost_type,
																output_id: 0,
																budget_line_id: 0,
																budget_line: '$budget_line',
																plan_id: 0,
																task_id:0,
																edit:$edit,
																sum_cost:$sum_cost
															}";
                                                        ?>
                                                        <div class="card-header">
                                                            <div class="row clearfix" style="margin-top:10px">
                                                                <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
                                                                    <h4><u>Evaluation Cost: <?= number_format($sum_cost, 2) ?> </u></h4>
                                                                </div>
                                                                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                                                                    <button type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" onclick="add_budgetline(<?= $budget_line_details ?>)" class="btn btn-success btn-sm" style="float:right">
                                                                        <?php echo $edit == 1 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="hidden" name="budgetlineid<?= $cost_type ?>[]" id="budgetlineid<?= $cost_type ?>" value="<?= $sum_cost ?>" class="task_costs">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th># </th>
                                                                            <th>Description </th>
                                                                            <th>Unit</th>
                                                                            <th>Unit Cost</th>
                                                                            <th>No. of Units</th>
                                                                            <th>Total Cost</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="budget_lines_tableC">
                                                                        <?php
                                                                        if ($totalRows_rs_output_cost_plan > 0) {
                                                                            $table_counter = 0;
                                                                            while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
                                                                                $table_counter++;
                                                                                $unit = $row_rsOther_cost_plan['unit'];
                                                                                $unit_cost = $row_rsOther_cost_plan['unit_cost'];
                                                                                $units_no = $row_rsOther_cost_plan['units_no'];
                                                                                $rmkid = $row_rsOther_cost_plan['id'];
                                                                                $description = $row_rsOther_cost_plan['description'];
                                                                                $total_cost = $unit_cost * $units_no;
                                                                                $unit_of_measure =  get_measurement($unit);
                                                                        ?>
                                                                                <tr id="row">
                                                                                    <td> <?= $table_counter ?></td>
                                                                                    <td> <?= $description ?></td>
                                                                                    <td> <?= $unit_of_measure ?></td>
                                                                                    <td> <?= number_format($unit_cost, 2) ?></td>
                                                                                    <td> <?= number_format($units_no) ?></td>
                                                                                    <td> <?= number_format($total_cost, 2) ?></td>
                                                                                </tr>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <?php
                                    $proceed = validate_mne();
                                    if ($proceed) {
                                        $assigned_responsible = check_if_assigned($projid, $workflow_stage, $project_sub_stage, 1);
                                        $stage = $workflow_stage;
                                        $approve_details = "{
                                                get_edit_details: 'details',
                                                projid:$projid,
                                                workflow_stage:$stage,
                                                project_directorate:$project_directorate,
                                                project_name:'$project',
                                                sub_stage:'$project_sub_stage',
                                            }";
                                        if ($assigned_responsible) {
                                            if ($approval_stage) {
                                    ?>
                                                <button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Approve</button>
                                            <?php
                                            } else {
                                                $data_entry_details = "{
                                                        get_edit_details: 'details',
                                                        projid:$projid,
                                                        workflow_stage:$workflow_stage,
                                                        project_directorate:$project_directorate,
                                                        project_name:'$project',
                                                        sub_stage:'$project_sub_stage',
                                                    }";
                                            ?>
                                                <button type="button" onclick="save_data_entry_project(<?= $data_entry_details ?>)" class="btn btn-success">Proceed</button>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->

    <!-- start outcome modal add edit -->
    <div class="modal fade" id="addOutcomeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title outcomemodaltitle" style="color:#fff" align="center" id="modal-title">Add Outcome Details</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="addoutcomeform" method="POST" name="addoutcomeform" action="" enctype="multipart/form-data" autocomplete="off">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="control-label" style="color:#0b548f; font-size:16px">Project: <u><?php echo $project; ?></u></label>
                                            </div>
                                            <br />
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="outcomeIndicator" class="control-label">Outcome *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="outcome" id="outcome" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="outcomeName" class="control-label">Indicator *:</label>
                                                <div class="form-input">
                                                    <select name="outcomeIndicator" id="outcomeIndicator" onchange="get_outcome_details()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                        <option value="">.... Select from list ....</option>
                                                        <?php
                                                        while ($row_rsOutcomeIndicators = $query_rsOutcomeIndicators->fetch()) {
                                                            $indicatorId = $row_rsOutcomeIndicators['indid'];
                                                        ?>
                                                            <option value="<?php echo $indicatorId ?>"><?php echo $row_rsOutcomeIndicators['indicator_name'] ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label class="control-label">Unit of Measure <span id="impunit"></span>*:</label>
                                                <div class="form-input">
                                                    <input type="text" name="outcomeunitofmeasure" readonly id="outcomeunitofmeasure" class="form-control" required="required">
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label for="outcom_calc_method" class="control-label">Calculation Method *:</label>
                                                <div class="form-input">
                                                    <input type="text" name="outcom_calc_method" readonly id="outcom_calc_method" class="form-control" required="required">
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label for="outcomeData" class="control-label">Source of data *:</label>
                                                <div class="form-input">
                                                    <select name="outcomedataSource" id="outcomedataSource" onchange="add_outcome_questions()" class="form-control" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                        <option value="">.... Select from list ....</option>
                                                        <option value="1">Survey</option>
                                                        <option value="2">Records</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label for="impactData" class="control-label">Evaluation Frequency*:</label>
                                                <div class="form-input">
                                                    <select data-id="0" name="outcome_frequency" id="outcome_frequency" class="form-control  selected_outcome_frequency" required="required">
                                                        <?php
                                                        $query_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1 AND level >=4");
                                                        $query_frequency->execute();
                                                        $totalRows_frequency = $query_frequency->rowCount();

                                                        $input = '<option value="">... Select from list ...</option>';
                                                        if ($totalRows_frequency > 0) {
                                                            while ($row_frequency = $query_frequency->fetch()) {
                                                                $input .= '<option value="' . $row_frequency['fqid'] . '">' . $row_frequency['frequency'] . ' </option>';
                                                            }
                                                        } else {
                                                            $input .= '<option value="">No defined frequency</option>';
                                                        }
                                                        echo $input;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label class="control-label">Number of Endline Evaluations *:</label>
                                                <div class="form-line">
                                                    <input type="hidden" name="evaluationNumber" id="evaluationNumber">
                                                    <input type="number" name="evaluationNumberFreq" id="evaluationNumberFreq" onchange="outcome_Evaluation()" onkeyup="outcome_Evaluation()" class="form-control" placeholder="Enter Number of endline evaluations" required="required">
                                                </div>
                                            </div>
                                            <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 outcomequestions">
                                                <label class="control-label">Main Question</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="55%">Question</th>
                                                                <th width="15%">Answer Type</th>
                                                                <th width="30%">Answer Labels</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" name="outcomemainquestion" id="outcomemainquestion" placeholder="Enter main outcome evaluation question" class="form-control querry" />
                                                                </td>
                                                                <td>
                                                                    <select data-id="0" name="outcomemainanswertype" id="outcomemainanswertype" class="form-control querry">
                                                                        <?php
                                                                        /* $input = '<option value="">... Select ...</option>';
                                                                        $input .= '<option value="1">Number</option>';
                                                                        $input .= '<option value="2">Mutiple Choice</option>';
                                                                        echo $input; */
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="outcome_main_answer_labels" placeholder="Enter comma seperated labels" class="form-control querry" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 outcomequestions">
                                                <label class="control-label">Other Question/s</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" id="outcome_questions_table" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th width="50%">Question</th>
                                                                <th width="15%">Answer Type</th>
                                                                <th width="25%">Answer Labels</th>
                                                                <th width="5%">
                                                                    <button type="button" name="addplus" id="addplus" onclick="add_row_question();" class="btn btn-success btn-sm">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="questions_table_body">
                                                            <?php
                                                            /* $orowno = 0;
                                                            if ($count_outcomeevalqstns > 0) {
                                                                do {
                                                                    $question = $row_outcomeevalqstns['question'];
                                                                    $orowno++; */
                                                            ?>
                                                                    <tr id="questionrow<? //= $orowno
                                                                                        ?>">
                                                                        <td> <? //= $orowno
                                                                                ?> </td>
                                                                        <td>
                                                                            <input type="text" name="outcomeotherquestions[]" id="questions<? //= $orowno
                                                                                                                                            ?>" value="<? //= $question
                                                                                                                                                        ?>" placeholder="Enter any other outcome evaluation question" class="form-control querry" />
                                                                        </td>
                                                                        <td>
                                                                            <select data-id="0" name="outcomeotheranswertype[]" id="answertype<? //= $orowno
                                                                                                                                                ?>" class="form-control querry">
                                                                                <?php
                                                                                /* $input = '<option value="">... Select ...</option>';
                                                                                $input .= '<option value="1">Number</option>';
                                                                                $input .= '<option value="2">Multiple Choice</option>';
                                                                                $input .= '<option value="3">Checkboxes</option>';
                                                                                $input .= '<option value="4">Dropdown</option>';
                                                                                $input .= '<option value="5">Text</option>';
                                                                                $input .= '<option value="6">File Upload</option>';

                                                                                echo $input; */
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="outcome_other_answer_label[]" id="outcome_other_answer_label<? //= $orowno
                                                                                                                                                                    ?>" placeholder="Enter comma seperated labels" class="form-control querry" />
                                                                        </td>

                                                                        <td>
                                                                            <?php
                                                                            // if ($orowno != 1) {
                                                                            ?>
                                                                                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_question("questionrow<? //= $orowno
                                                                                                                                                                                            ?>")'>
                                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                                </button>
                                                                            <?php
                                                                            //}
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                /* } while ($row_outcomeevalqstns = $query_outcomeevalqstns->fetch());
                                                            } else {
                                                                $orowno++; */
                                                                ?>
                                                                <tr id="questionrow90">
                                                                    <td> 1 </td>
                                                                    <td>
                                                                        <input type="text" name="outcomeotherquestions[]" id="questions90" value="" placeholder="Enter any other outcome evaluation question" class="form-control querry" />
                                                                    </td>
                                                                    <td>
                                                                        <select data-id="0" name="outcomeotheranswertype[]" id="answertype<? //= $orowno
                                                                                                                                            ?>" class="form-control querry">
                                                                            <?php
                                                                            /*  $input = '<option value="">... Select ...</option>';
                                                                            $input .= '<option value="1">Number</option>';
                                                                            $input .= '<option value="2">Multiple Choice</option>';
                                                                            $input .= '<option value="3">Checkboxes</option>';
                                                                            $input .= '<option value="4">Dropdown</option>';
                                                                            $input .= '<option value="5">Text</option>';
                                                                            $input .= '<option value="6">File Upload</option>';

                                                                            echo $input; */
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="outcome_other_answer_label[]" id="outcome_other_answer_label<? //= $orowno
                                                                                                                                                                ?>" placeholder="Enter comma seperated labels" class="form-control querry" />
                                                                    </td>
                                                                    <td>

                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            // }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>-->
                                            <div class="modal-footer">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    <input type="hidden" name="addoutcome" id="addoutcome" value="addoutcome">
                                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
                                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="outcome-tag-form-submit" value="Save" />
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
    <!-- End Outcome Modal add edit -->

    <!-- start Impact modal add edit -->

    <div class="modal fade" id="addImpactModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title impactmodaltitle" style="color:#fff" align="center" id="modal-title">Add Impact Details</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="addimpactform" method="POST" name="addimpactform" action="" enctype="multipart/form-data" autocomplete="off">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="control-label" style="color:#0b548f; font-size:16px">Project: <u><?php echo $project; ?></u></label>
                                            </div>
                                            <br />
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="impact" class="control-label">Impact *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="impact" id="impact" placeholder="Enter the impact" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="impactName" class="control-label">Indicator *:</label>
                                                <div class="form-input">
                                                    <select name="impactIndicator" id="impactIndicator" onchange="get_impact_details()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                        <option value="">.... Select from list ....</option>
                                                        <?php
                                                        do {
                                                            $indId = $row_impactIndicators['indid'];
                                                        ?>
                                                            <option value="<?php echo $indId ?>"><?php echo $row_impactIndicators['indicator_name'] ?></option>
                                                        <?php
                                                        } while ($row_impactIndicators = $query_impactIndicators->fetch());
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label class="control-label">Unit of Measure <span id="impunit"></span>*:</label>
                                                <div class="form-input">
                                                    <input type="text" name="impactunitofmeasure" readonly id="impactunitofmeasure" class="form-control" placeholder="Select change" required="required">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label for="impact_calc_method" class="control-label">Calculation Method *:</label>
                                                <div class="form-input">
                                                    <input type="text" name="impact_calc_method" readonly id="impact_calc_method" class="form-control" placeholder="First select change to be measured" required="required">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label for="impactData" class="control-label">Source of data *:</label>
                                                <div class="form-input">
                                                    <select name="impactdataSource" id="impactdataSource" onchange="add_impact_questions()" class="form-control" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                        <option value="">.... Select from list ....</option>
                                                        <option value="1">Survey</option>
                                                        <option value="2">Records</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label for="impactData" class="control-label">Evaluation Frequency*:</label>
                                                <div class="form-input">
                                                    <select data-id="0" name="impact_frequency" id="impact_frequency" class="form-control  selected_outcome" required="required">
                                                        <?php
                                                        $query_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1 AND level >=4");
                                                        $query_frequency->execute();
                                                        $totalRows_frequency = $query_frequency->rowCount();

                                                        $input = '<option value="">... Select from list ...</option>';
                                                        if ($totalRows_frequency > 0) {
                                                            while ($row_frequency = $query_frequency->fetch()) {
                                                                $input .= '<option value="' . $row_frequency['fqid'] . '">' . $row_frequency['frequency'] . ' </option>';
                                                            }
                                                        } else {
                                                            $input .= '<option value="">No defined frequency</option>';
                                                        }
                                                        echo $input;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label class="control-label">Number of Endline Evaluations *:</label>
                                                <div class="form-line">
                                                    <input type="hidden" name="evaluationNumber" id="evaluationNumber">
                                                    <input type="number" name="evaluationNumberFreq" id="evaluationNumberFreq" onchange="outcome_Evaluation()" onkeyup="outcome_Evaluation()" class="form-control" placeholder="Enter Number of endline evaluations" required="required">
                                                </div>
                                            </div>
                                            <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 impactquestions">
                                                <label class="control-label">Main Question</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="55%">Question</th>
                                                                <th width="15%">Answer Type</th>
                                                                <th width="30%">Answer Labels</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" name="impactmainquestion" id="impactmainquestion" placeholder="Enter main impact evaluation question" class="form-control querry" />
                                                                </td>
                                                                <td>
                                                                    <select data-id="0" name="impactmainanswertype" id="impactmainanswertype" class="form-control querry">
                                                                        <?php
                                                                        /* $impactinput = '<option value="">... Select ...</option>';
                                                                        $impactinput .= '<option value="1">Number</option>';
                                                                        $impactinput .= '<option value="2">Mutiple Choice</option>';
                                                                        echo $impactinput; */
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="impact_main_answer_labels" placeholder="Enter comma seperated labels" class="form-control querry" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 impactquestions">
                                                <label class="control-label">Other Question/s</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th width="50%">Question</th>
                                                                <th width="15%">Answer Type</th>
                                                                <th width="25%">Answer Labels</th>
                                                                <th width="5%">
                                                                    <button type="button" name="addplus" id="addplus" onclick="add_row_impact_question();" class="btn btn-success btn-sm">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="impact_questions_table_body">
                                                            <?php
                                                            /*  $iprowno = 0;
                                                            $iprowno++; */
                                                            ?>
                                                            <tr id="impactquestionrow90">
                                                                <td> 1 </td>
                                                                <td>
                                                                    <input type="text" name="impactquestions[]" id="impactquestions90" value="" placeholder="Enter any other impact evaluation questions" class="form-control impactquerry" />
                                                                </td>
                                                                <td>
                                                                    <select data-id="0" name="impactanswertype[]" id="impactanswertype<? //= $iprowno
                                                                                                                                        ?>" class="form-control impactquerry">
                                                                        <?php
                                                                        /*  $impactotherinput = '<option value="">... Select ...</option>';
                                                                        $impactotherinput .= '<option value="1">Number</option>';
                                                                        $impactotherinput .= '<option value="2">Mutiple Choice</option>';
                                                                        $impactotherinput .= '<option value="3">Checkboxes</option>';
                                                                        $impactotherinput .= '<option value="4">Dropdown</option>';
                                                                        $impactotherinput .= '<option value="5">Text</option>';
                                                                        $impactotherinput .= '<option value="6">File Upload</option>';
                                                                        echo $impactotherinput; */
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="impact_other_answer_label[]" id="impact_other_answer_label<? //= $iprowno
                                                                                                                                                        ?>" placeholder="Enter comma seperated labels" class="form-control querry" />
                                                                </td>
                                                                <td>

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>-->
                                            <div class="modal-footer">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    <input type="hidden" name="addimpact" id="addimpact" value="addimpact">
                                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
                                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="impact-tag-form-submit" value="Save" />
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
    <!-- End Impact Modal add edit -->

    <!-- add item -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Add Other Details</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="add_modal_form">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-calendar" aria-hidden="true"></i> Budgetline Details
                                            </legend>
                                            <div id="others_budget_line">
                                                <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item No.</th>
                                                                        <th>Description </th>
                                                                        <th>Unit of Measure</th>
                                                                        <th>No. of Units </th>
                                                                        <th>Unit Cost</th>
                                                                        <th>Total Cost (Ksh)</th>
                                                                        <th style="width:2%">
                                                                            <button type="button" name="addplus" id="addplus_financier" onclick="add_budget_costline();" class="btn btn-success btn-sm">
                                                                                <span class="glyphicon glyphicon-plus">
                                                                                </span>
                                                                            </button>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="budget_lines_values_table">
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text" name="order[]" class="form-control sequence" id="order1">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="description[]" class="form-control" id="description">
                                                                        </td>
                                                                        <td>
                                                                            <select name="unit_of_measure[]" id="unit_of_measurerow1" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
                                                                                <?php
                                                                                $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE active=1");
                                                                                $query_rsIndUnit->execute();
                                                                                $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                                $unit_options = '<option value="">..Select Unit of Measure..</option>';
                                                                                if ($totalRows_rsIndUnit > 0) {
                                                                                    while ($row_rsIndUnit = $query_rsIndUnit->fetch()) {
                                                                                        $unit_id = $row_rsIndUnit['id'];
                                                                                        $unit = $row_rsIndUnit['unit'];
                                                                                        $unit_options .= '<option value="' . $unit_id . '">' . $unit . '</option>';
                                                                                    }
                                                                                }
                                                                                echo $unit_options;
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="unit_cost[]" min="0" class="form-control" onchange="calculate_total_cost(1)" onkeyup="calculate_total_cost(1)" id="unit_cost1">
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="no_units[]" min="0" class="form-control" onchange="calculate_total_cost(1)" onkeyup="calculate_total_cost(1)" id="no_units1">
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" name="subtask_id[]" class="form-control" id="subtask_id1" value="0" />
                                                                            <input type="hidden" name="task_type[]" class="form-control" id="task_type1" value="1" />
                                                                            <input type="hidden" name="subtotal_amount[]" id="subtotal_amount1" class="subtotal_amount subamount" value="">
                                                                            <span id="subtotal_cost1" style="color:red"></span>
                                                                        </td>
                                                                        <td style="width:2%"></td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot id="budget_line_foot">
                                                                    <tr>
                                                                        <td><strong>Balance</strong></td>
                                                                        <td>
                                                                            <input type="hidden" name="remaining_balance" id="remaining_balance" value="<?= $mne_budget ?>" />
                                                                            <input type="text" name="remaining_balance1" value="<?= number_format($mne_budget, 2) ?>" id="remaining_balance1" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                        </td>
                                                                        <td><strong>Sub Total</strong></td>
                                                                        <td>
                                                                            <input type="text" name="subtotal_amount" value="" id="psub_total_amount3" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                        </td>
                                                                        <td> <strong>% Sub Total</strong></td>
                                                                        <td colspan='2'>
                                                                            <input type="text" name="subtotal_percentage" value="%" id="psub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->
                        <div class="modal-footer">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                <input type="hidden" name="user_name" id="username" value="<?= $user_name ?>">
                                <input type="hidden" name="store" id="store" value="new">
                                <input type="hidden" name="task_id" id="task_id" value="">
                                <input type="hidden" name="budget_line_id" id="budget_line_id" value="">
                                <input type="hidden" name="cost_type" id="cost_type" value="">
                                <input type="hidden" name="output_id" id="output_id" value="">
                                <input type="hidden" name="plan_id" id="plan_id" value="">
                                <input type="hidden" name="mne_budget" id="mne_budget" value="<?= $mne_budget ?>">
                                <button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value="">
                                    Save
                                </button>
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            </div>
                        </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->


<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>

<?php
$details = "{
    members: '" . get_members(0) . "',
}"
?>
<script>
    const redirect_url = "view-mne-plan.php";

    const details = <?= $details ?>
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="assets/js/mneplan/add-project-mne-plan.js"></script>
<script src="assets/js/mneplan/index.js"></script>
<script src="assets/js/master/index.js"></script>