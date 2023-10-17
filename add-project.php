<?php
require('includes/head.php');

if ($permission) {
    function generate_key($str_length)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $str_length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    $program_type = $planid = $progid = $projid = $projcode = $projname = $projdescription = $projtype = $projendyear = "";
    $projbudget = $projfscyear = $projduration = $projevaluation = $projimpact  = $projimpact = "";
    $project_budget = 0;
    $projcommunity = $projlga = $projlocation = "";
    $projcategory = $projstatus = "";
    $progname =  $program_start_date = $program_end_date =  $program_duration = $projectendYearDate = $target_beneficiaries = "";
    $key_unique = generate_key(10);

    if (isset($_GET['projid'])) {
        $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : "";
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProgjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();


        if ($totalRows_rsProjects > 0) {
            $progid = $row_rsProgjects['progid'];
            $projcode = $row_rsProgjects['projcode'];
            $projname = $row_rsProgjects['projname'];
            $projdescription = $row_rsProgjects['projdesc'];
            $projtype = $row_rsProgjects['projtype'];
            $projbudget = $row_rsProgjects['projbudget'];
            $projfscyear = $row_rsProgjects['projfscyear'];
            $projduration = $row_rsProgjects['projduration'];
            $projevaluation = $row_rsProgjects['projevaluation'];
            $projcommunity = $row_rsProgjects['projcommunity'];
            $projlga = $row_rsProgjects['projlga'];
            $projlocation = $row_rsProgjects['projlocation'];
            $projcategory = $row_rsProgjects['projcategory'];
            $projstatus = $row_rsProgjects['projstatus'];
            $projimpact = $row_rsProgjects['projimpact'];
            $key_unique = $row_rsProgjects['key_unique'];
            $target_beneficiaries = $row_rsProgjects['beneficiaries'];
            $project_budget = $row_rsProgjects['projcost'];

            $query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id ='$projfscyear'");
            $query_rsFscYear->execute();
            $row_rsFscYear = $query_rsFscYear->fetch();
            $projstartYear = $row_rsFscYear ? $row_rsFscYear['yr'] : "";

            $Date = $projstartYear . "-07-01";
            $projectendYearDate =  date('Y-m-d', strtotime($Date . ' + ' . $projduration . ' days'));
        }
    } else if (isset($_GET['progid'])) {
        $decode_progid = (isset($_GET['progid']) && !empty($_GET["progid"])) ? base64_decode($_GET['progid']) : "";
        $progid_array = explode("progid54321", $decode_progid);
        $progid = $progid_array[1];
    }

    $query_rsBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_progdetails WHERE progid=:progid");
    $query_rsBudget->execute(array(":progid" => $progid));
    $row_rsPBudget = $query_rsBudget->fetch();
    $programs_budget = $row_rsPBudget['budget'] != null ? $row_rsPBudget['budget'] : 0;

    $query_rsProjectBudget = $db->prepare("SELECT  projcost FROM tbl_projects WHERE progid=:progid");
    $query_rsProjectBudget->execute(array(":progid" => $progid));
    $row_rsProjectBudget = $query_rsProjectBudget->fetch();
    $project_program_budget = $row_rsProjectBudget['projcost']  ? $row_rsProjectBudget['projcost'] : 0;


    $program_budget = ($programs_budget - $project_program_budget) + $project_budget;


    $query_rsProgram = $db->prepare("SELECT * FROM tbl_programs WHERE deleted='0' and progid=:progid");
    $query_rsProgram->execute(array(":progid" => $progid));
    $row_rsProgram = $query_rsProgram->fetch();
    $totalRows_rsProgram = $query_rsProgram->rowCount();
    if ($totalRows_rsProgram > 0) {
        $progname = $row_rsProgram['progname'];
        $program_start_year = $row_rsProgram['syear'];
        $program_duration_years = $row_rsProgram['years'];
        $program_type = $row_rsProgram['program_type'];
        $planid = $row_rsProgram['strategic_plan'];

        $program_end_year = $program_start_year + $program_duration_years;

        $program_start_date = "01-07-$program_start_year";
        $program_end_date = "30-06-$program_end_year";
        $program_duration_difference = strtotime($program_end_date) - strtotime($program_start_date);
        $program_duration = abs(round($program_duration_difference / 86400)) + 1;
    }


    function get_financial_years($progid, $year_id)
    {
        global $db;
        $query_Years = $db->prepare("SELECT DISTINCT year FROM `tbl_progdetails` WHERE progid =:progid ORDER BY year ASC");
        $query_Years->execute(array(":progid" => $progid));
        $row_Years = $query_Years->fetch();
        $totalRows_Years = $query_Years->rowCount();
        $finacial_years = "";
        if ($totalRows_Years > 0) {
            do {
                $year = $row_Years['year'];
                $query_rsYear =  $db->prepare("SELECT * FROM tbl_fiscal_year where yr ='$year'");
                $query_rsYear->execute();
                $row_rsYear = $query_rsYear->fetch();

                $yrstartdate = $row_rsYear["sdate"];
                $yrenddate = $row_rsYear["edate"];
                $currdatetime = date("Y-m-d H:i:s");

                // if ($currdatetime <= $yrenddate) {
                $finyear = $row_rsYear['year'];
                $finyearid = $row_rsYear['id'];
                $yr = $row_rsYear["yr"];
                $selected = $finyearid == $year_id ? 'selected' : '';
                $finacial_years .=  '<option value="' . $finyearid . '" ' . $selected . '>' . $finyear . '</option>';
                // }
            } while ($row_Years = $query_Years->fetch());
        }
        return $finacial_years;
    }

    function get_implimentation_method($imp_id)
    {
        global $db;
        $query_rsProjImplMethod =  $db->prepare("SELECT DISTINCT id, method FROM tbl_project_implementation_method");
        $query_rsProjImplMethod->execute();
        $row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
        $totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();
        $options = "";
        if ($totalRows_rsProjImplMethod > 0) {
            do {
                $implementation_id = $row_rsProjImplMethod['id'];
                $method = $row_rsProjImplMethod['method'];
                $selected = $imp_id == $implementation_id ? "selected" : "";
                $options .= '<option value="' . $implementation_id . '" ' . $selected . '>' . $method . '</option>';
            } while ($row_rsProjImplMethod = $query_rsProjImplMethod->fetch());
        }
        return $options;
    }

    function get_level1($projcommunity)
    {
        global $db;
        $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY id ASC");
        $query_rsComm->execute();
        $row_rsComm = $query_rsComm->fetch();
        $totalRows_rsComm = $query_rsComm->rowCount();

        if ($totalRows_rsComm) {
            $options = '';
            $id = [];
            $projcommunity = explode(',', $projcommunity);
            do {
                $comm = $row_rsComm['id'];
                $state =    $row_rsComm['state'];
                $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm AND active=1");
                $query_ward->execute(array(":comm" => $comm));
                while ($row = $query_ward->fetch()) {
                    $projlga = $row['id'];
                    $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                    $query_rsLocations->execute(array(":id" => $projlga));
                    $total_locations = $query_rsLocations->rowCount();
                    if ($total_locations > 0) {
                        if (!in_array($comm, $id)) {
                            $selected = in_array($comm, $projcommunity) ? 'selected' : "";
                            $options .= '<option value="' . $comm . '" ' . $selected . '>' . $state . '</option>';
                        }
                        $id[] = $row_rsComm['id'];
                    }
                }
            } while ($row_rsComm = $query_rsComm->fetch());
        }
        return $options;
    }

    function get_level2($projcommunity, $projlga)
    {
        global $db;
        $data = '';
        $ward = explode(",", $projlga);
        $community = explode(",", $projcommunity);
        if (count($community) > 0) {
            for ($j = 0; $j < count($community); $j++) {
                $query_Community = $db->prepare("SELECT id, state FROM tbl_state WHERE id='$community[$j]'");
                $query_Community->execute();
                $row_community = $query_Community->fetch();
                $level1 = $row_community['state'];

                $data .= '
                <optgroup label="' . $level1 . '"> ';
                $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$community[$j]'");
                $query_ward->execute();
                while ($row = $query_ward->fetch()) {
                    $level2 = $row['id'];
                    $state = $row['state'];

                    $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                    $query_rsLocations->execute(array(":id" => $level2));
                    $total_locations = $query_rsLocations->rowCount();
                    if ($total_locations > 0) {
                        $selected = in_array($level2, $ward) ? 'selected' : "";
                        $data .= '<option value="' . $level2 . '" ' . $selected . '> ' . $state . '</option>';
                    }
                }
                $data .= '
                    <optgroup>';
            }
        }
        return $data;
    }

    function get_outputs($progid, $indicator)
    {
        global $db;
        $query_rsIndicator =  $db->prepare("SELECT g.id, i.indicator_name FROM tbl_progdetails g INNER JOIN tbl_indicator i ON i.indid = g.indicator where indicator =:indicator AND progid=:progid ");
        $query_rsIndicator->execute(array("indicator" => $indicator, "progid" => $progid));
        $count_rsIndicator = $query_rsIndicator->fetch();
        $options = "";
        if ($count_rsIndicator > 0) {
            while ($row_rsIndicator = $query_rsIndicator->fetch()) {
                $projoutput = $row_rsIndicator['indicator_name'];
                $opid = $row_rsIndicator['id'];
                $options .= '<option value="' . $opid . '">' . $projoutput . '</option>';
            }
        }
    }

    $stage = 0;
    $query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projstage=:stage and projid=:projid");
    $query_rsFile->execute(array(":stage" => $stage, ":projid" => $projid));
    $row_rsFile = $query_rsFile->fetch();
    $totalRows_rsFile = $query_rsFile->rowCount();

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addprojectfrm")) {
        $planid = $_POST['planid'];
        $program_type =  $_POST['program_type'];
        $strategicplanid = base64_encode("strplan1{$planid}");
        $redirect_url = ($program_type == 1) ? "strategic-plan-projects.php?plan=" . $strategicplanid : "all-programs";
        $msg = 'Project Successfully Added';
        $results = "<script type=\"text/javascript\">
            swal({
                title: \"Success!\",
                text: \" $msg\",
                type: 'Success',
                timer: 2000,
                'icon':'success',
            showConfirmButton: false });
            setTimeout(function(){
                window.location.href = '$redirect_url';
            }, 2000);
        </script>";
    }


    $query_rsSites =  $db->prepare("SELECT state_id FROM tbl_project_sites WHERE projid =:projid GROUP BY state_id");
    $query_rsSites->execute(array(":projid" => $projid));
    $totalRows_rsSites = $query_rsSites->rowCount();
?>
    <link rel="stylesheet" href="css/addprojects.css">
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?= $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <button onclick="history.back()" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">
                                Go Back
                            </button>
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
                            <div class="stepwizard" style="margin-bottom:15px">
                                <div class="stepwizard-row setup-panel bg-light-blue" style="margin-top:10px">
                                    <div class="stepwizard-step">
                                        <a href="#step-1" type="button" data-toggle="tab" class="btn btn-primary btn-circle">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        </a>
                                        <p>Project Details</p>
                                    </div>
                                    <div class="stepwizard-step">
                                        <a href="#step-3" type="button" data-toggle="tab" class="btn btn-default btn-circle disabled">
                                            <i class="fa fa-bullseye fa-3x" aria-hidden="true"></i>
                                        </a>
                                        <p>Documents</p>
                                    </div>
                                    <div class="stepwizard-step">
                                        <a href="#step-4" type="button" data-toggle="tab" onclick="display_finish()" class="btn btn-default btn-circle disabled">
                                            <i class="fa fa-bullseye fa-3x" aria-hidden="true"></i>
                                        </a>
                                        <p>Finish</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ADD PROJECT DETAILS</legend>
                                <form role="form" id="project_details" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="control-label">Program Name*:</label>
                                        <div class="form-line">
                                            <input type="hidden" name="progid" id="progid" class="form-control" value="<?= $progid ?>">
                                            <input type="text" name="program_name" id="prog" value="<?= $progname ?>" placeholder="Please enter name of your project" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                        <label class="control-label">Program Start Year*:</label>
                                        <div class="form-line">
                                            <input type="text" name="progstartyear" id="progstartyear" value="<?= $program_start_year ?>" placeholder="" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                        <label class="control-label">Program End Year*:</label>
                                        <div class="form-line">
                                            <input type="text" name="progendyear" id="progendyear" value="<?= $program_end_year ?>" placeholder="" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                        <label class="control-label">Program Duration*:</label>
                                        <div class="form-line">
                                            <input type="hidden" name="program_duration" value="<?= $program_duration ?>">
                                            <input type="text" name="progduration" id="progduration" value="<?= $program_duration_years ?>" placeholder="" class=" form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="control-label">Project Code (Eg. 2018/12/AB23)*:</label>
                                        <span id="gt" style="display:none; color:#fff; background-color:#F44336; padding:5px"> Code Exists </span>
                                        <div class="form-line">
                                            <input type="text" name="projcode" onblur="validate_projcode()" id="projcode" value="<?= $projcode ?>" placeholder="Enter Project Code" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required="required">
                                            <span id="projcodemsg" style="color:red"> </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <zlabel class="control-label">Project Name *:</zlabel>
                                        <div class="form-line">
                                            <input type="text" name="projname" id="projname" placeholder="Enter Project Name" value="<?= $projname ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Project Description :</label>
                                        <div class="form-line">
                                            <input type="text" name="projdescription" id="projdescription" value="<?= $projdescription ?>" placeholder="Enter Project description" class="form-control" style="border:#CCC thin solid; border-radius: 5px">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label">Project Start Financial Year *:</label>
                                        <div class="form-line">
                                            <select name="projfscyear1" id="projfscyear1" onchange="project_duration_validate()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                                <option value="">.... Select Year from list ....</option>
                                                <?= get_financial_years($progid, $projfscyear) ?>
                                            </select>
                                            <span id="projfscyearmsg1" style="color:red"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="projduration">Project Duration (Days) *:</label>(<span id="projdurationmsg" style="color:darkgoldenrod"><?= $program_duration ?></span>)
                                        <div class="form-input">
                                            <input type="number" name="projduration1" min="0" value="<?= $projduration ?>" onkeyup="project_duration_validate()" onchange="project_duration_validate()" id="projduration1" placeholder="Enter" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="projendyear">Project End Financial Year *:</label>
                                        <input type="text" name="projendyear" id="projendyear" value="<?= $projectendYearDate ?>" class="form-control" disabled>
                                        <span id="" style="color:red"></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="project_budget">Project Budget *:<span class="text-danger">(Ksh. <?= number_format($program_budget) ?>)</span></label>
                                        <input type="number" name="project_budget" min="1" id="project_budget" value="<?= $project_budget ?>" onchange="calculate_project_budget()" onkeyup="calculate_project_budget()" class="form-control" required>
                                        <input type="hidden" name="program_budget_ceiling" id="program_budget_hidden" value="<?= $program_budget ?>">
                                        <span id="" style="color:red"></span>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="beneficiary">Target Beneficiaries *:</label>
                                        <input type="text" name="beneficiary" id="beneficiary" value="<?= $target_beneficiaries ?>" class="form-control">
                                        <span id="" style="color:red"></span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="" class="control-label">Outcome Evaluation Required? *:</label>
                                        <div class="form-line">
                                            <input name="projevaluation" type="radio" value="1" onchange="show_impact(1)" <?= $projevaluation == 1 && $projid != "" ? "checked" : "" ?> id="evaluation1" class="with-gap radio-col-green evaluation" required="required" />
                                            <label for="evaluation1">YES</label>
                                            <input name="projevaluation" type="radio" value="0" onchange="show_impact(0)" <?= $projevaluation == 0 && $projid != "" ? "checked" : "" ?> id="evaluation2" class="with-gap radio-col-red evaluation" required="required" />
                                            <label for="evaluation2">NO</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" id="impact_div">
                                        <label for="" class="control-label">Impact Evaluation Required? *:</label>
                                        <div class="form-line">
                                            <input name="impact" type="radio" value="1" id="impact1" <?= $projimpact == 1 && $projid != "" ? "checked" : "" ?> class="with-gap radio-col-green impact" />
                                            <label for="impact1">YES</label>
                                            <input name="impact" type="radio" value="0" id="impact2" <?= $projimpact == 0 && $projid != "" ? "checked" : "" ?> class="with-gap radio-col-red impact" />
                                            <label for="impact2">NO</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="" class="control-label">Project Sites Required? *:</label>
                                        <div class="form-line">
                                            <input name="project_sites" type="radio" value="1" onchange="hide_project_site_table(1)" <?= $totalRows_rsSites > 0 && $projid != "" ? "checked" : "" ?> id="project_sites1" class="with-gap radio-col-green project_site" required="required" />
                                            <label for="project_sites1">YES</label>
                                            <input name="project_sites" type="radio" value="0" onchange="hide_project_site_table(0)" <?= $totalRows_rsSites == 0 && $projid != "" ? "checked" : "" ?> id="project_sites2" class="with-gap radio-col-red project_site" required="required" />
                                            <label for="project_sites2">NO</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label class="control-label">Implementation Method *:</label>
                                        <div class="form-line">
                                            <select name="projimplmethod" id="projimplmethod" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                <option value="">.... Select the method ....</option>
                                                <?= get_implimentation_method($projcategory) ?>
                                            </select>
                                        </div>
                                    </div>
                                    <script>
                                        function calculate_project_budget() {
                                            var project_budget = $("#project_budget").val();
                                            var program_budget = $("#program_budget_hidden").val();
                                            if (program_budget != '' && project_budget != '') {
                                                program_budget = parseFloat(program_budget);
                                                project_budget = parseFloat(project_budget);
                                                console.log(program_budget, project_budget);
                                                if (project_budget > 0 && program_budget > 0) {
                                                    if (project_budget > program_budget) {
                                                        $('#project_budget').val("");
                                                        error_alert("You cannot exceed program budget");
                                                    }
                                                }
                                            }
                                        }
                                    </script>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="control-label">Project <?= $level1label ?>*:</label>
                                        <div class="form-line">
                                            <select name="projcommunity[]" id="projcommunity" onchange="get_conservancy()" data-actions-box="true" class="form-control show-tick selectpicker" title="Choose Multipe" multiple style="border:#CCC thin solid; border-radius:5px; width:98%; padding-left:50px" required>
                                                <?= get_level1($projcommunity) ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="control-label">Project <?= $level2label ?>*:</label>
                                        <div class="form-line">
                                            <select name="projlga[]" id="projlga" class="form-control show-tick selectpicker" multiple data-actions-box="true" title="Choose Multipe" style="border:#CCC thin solid; border-radius:5px; width:98%; padding-right:0px" required>
                                                <?php
                                                if ($projid == "") {
                                                ?>
                                                    <option value="" style="padding-right:0px">.... Select <?= $level1label ?> First ....</option>
                                                <?php
                                                }
                                                ?>
                                                <?= get_level2($projcommunity, $projlga) ?>
                                            </select>
                                        </div>
                                    </div>
                                    <fieldset class="scheduler-border" id="project_site_table">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> Project Sites </legend>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="projoutputTable">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="project_sites_table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="40%"><?= $level2label ?></th>
                                                            <th width="50%">Sites </th>
                                                            <th width="5%">
                                                                <button type="button" name="addplus" id="add_project_site" onclick="add_site_row()" class="btn btn-success btn-sm">
                                                                    <span class="glyphicon glyphicon-plus">
                                                                    </span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="project_sites_table_body">
                                                        <tr></tr>
                                                        <?php

                                                        function get_sites($projid, $state_id)
                                                        {
                                                            global $db;
                                                            $query_rsSites =  $db->prepare("SELECT * FROM tbl_project_sites WHERE projid =:projid AND state_id=:state_id");
                                                            $query_rsSites->execute(array(":projid" => $projid, ":state_id" => $state_id));
                                                            $totalRows_rsSites = $query_rsSites->rowCount();
                                                            $sites = [];
                                                            if ($totalRows_rsSites > 0) {
                                                                while ($row_rsSites = $query_rsSites->fetch()) {
                                                                    $sites[] = $row_rsSites['site'];
                                                                }
                                                            }
                                                            return implode(",", $sites);
                                                        }

                                                        function get_states($stid, $projlga)
                                                        {
                                                            global $db;
                                                            $projlga = explode(",", $projlga);
                                                            $count = count($projlga);
                                                            $states  = '';
                                                            for ($i = 0; $i < $count; $i++) {
                                                                $state_id = $projlga[$i];
                                                                $query_rsSites =  $db->prepare("SELECT * FROM tbl_state WHERE id=:state_id");
                                                                $query_rsSites->execute(array(":state_id" => $state_id));
                                                                $totalRows_rsSites = $query_rsSites->rowCount();
                                                                if ($totalRows_rsSites > 0) {
                                                                    $row_rsSites = $query_rsSites->fetch();
                                                                    $state   = $row_rsSites['state'];
                                                                    $selected = $stid == $state_id ? "selected" : "";
                                                                    $states .= '<option value="' . $state_id . '"  ' . $selected . '>' . $state . '</option>';
                                                                }
                                                            }
                                                            return $states;
                                                        }



                                                        if ($totalRows_rsSites > 0) {
                                                            $rowno = 0;
                                                            while ($row_rsSites = $query_rsSites->fetch()) {
                                                                $rowno++;
                                                                $state_id = $row_rsSites['state_id'];
                                                                $states = get_states($state_id, $projlga);
                                                                $sites = get_sites($projid, $state_id);
                                                        ?>
                                                                <tr id="siterow<?= $rowno ?>">
                                                                    <td><?= $rowno ?></td>
                                                                    <td>
                                                                        <select name="lvid[]" id="lvidrow<?= $rowno ?>" class="form-control lvidstates" required="required">
                                                                            <option value="">Select <?= $level2label ?> from list</option>
                                                                            <?= $states ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="site[]" id="siterow<?= $rowno ?>" value="<?= $sites ?>" placeholder="Enter" class="form-control" required />
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" name="addplus" id="add_project_site" onclick='delete_row_sites("siterow<?= $rowno ?>")' class="btn btn-danger btn-sm">
                                                                            <span class="glyphicon glyphicon-minus">
                                                                            </span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr id="removeSTr" class="text-center">
                                                                <td colspan="5">Add Project Sites!!</td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <ul class="list-inline text-center">
                                            <li><button class="btn btn-success btn-sm" id="project_details_id" type="submit"><?= $totalRows_rsSites > 0 ? "Edit" : "Save" ?></button></li>
                                        </ul>
                                        <ul class="list-inline pull-right">
                                            <input type="hidden" name="key_unique" id="p_key_unique" value="<?= $key_unique ?>">
                                            <input type="hidden" name="project_id" id="project_id" class="project_id" value="<?= $projid ?>">
                                            <input type="hidden" name="sites_list" id="sites_list" class="sites_list">
                                            <input type="hidden" name="insert_project" id="insert_project">
                                            <li><button class="btn btn-primary nextBtn btn-sm " type="button">Next</button> </li>
                                        </ul>
                                    </div>
                                </form>
                            </fieldset>
                            <fieldset class="scheduler-border row setup-content" id="step-3">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FILES</legend>
                                <form role="form" id="files_details" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <?php
                                    if ($totalRows_rsFile > 0) {
                                    ?>
                                        <div class="row clearfix " id="rowcontainerrow">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card">
                                                    <div class="header">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                                            <h5 style="color:#FF5722"><strong> FILES </strong></h5>
                                                        </div>
                                                    </div>
                                                    <div class="body">
                                                        <div class="body table-responsive">
                                                            <table class="table table-bordered" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width:2%">#</th>
                                                                        <th style="width:68%">Purpose</th>
                                                                        <th style="width:28%">Attachment</th>
                                                                        <th style="width:2%">
                                                                            Delete
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="attachment_table">
                                                                    <?php
                                                                    $counter = 0;
                                                                    do {
                                                                        $pdfname = $row_rsFile['filename'];
                                                                        $filecategory = $row_rsFile['fcategory'];
                                                                        $ext = $row_rsFile['ftype'];
                                                                        $filepath = $row_rsFile['floc'];
                                                                        $fid = $row_rsFile['fid'];
                                                                        $attachmentPurpose = $row_rsFile['reason'];
                                                                        $counter++;
                                                                    ?>
                                                                        <tr id="mtng<?= $fid ?>">
                                                                            <td>
                                                                                <?= $counter ?>
                                                                            </td>
                                                                            <td>
                                                                                <?= $attachmentPurpose ?>
                                                                                <input type="hidden" name="fid[]" id="fid" class="" value="<?= $fid  ?>">
                                                                                <input type="hidden" name="ef[]" id="t" class="eattachment_purpose" value="<?= $attachmentPurpose  ?>">
                                                                            </td>
                                                                            <td>
                                                                                <?= $pdfname ?>
                                                                                <input type="hidden" name="adft[]" id="fid" class="eattachment_file" value="<?= $pdfname  ?>">
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-danger btn-sm" onclick='delete_attachment("mtng<?= $fid ?>")'>
                                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    } while ($row_rsFile = $query_rsFile->fetch());
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="row clearfix " id="">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card">
                                                <div class="header">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                                        <h5 style="color:#FF5722"><strong> Add new file/s </strong></h5>
                                                    </div>
                                                </div>
                                                <div class="body">
                                                    <div class="body table-responsive">
                                                        <table class="table table-bordered" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:2%">#</th>
                                                                    <th style="width:68%">Attachment</th>
                                                                    <th style="width:28%">Purpose</th>
                                                                    <th style="width:2%">
                                                                        <button type="button" name="addplus1" onclick="add_row_files_edit();" title="Add another document" class="btn btn-success btn-sm">
                                                                            <span class="glyphicon glyphicon-plus">
                                                                            </span>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="meetings_table_edit">
                                                                <tr></tr>
                                                                <tr id="add_new_file" class="text-c
                                                                enter">
                                                                    <td colspan="4"> Add file </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <ul class="list-inline text-center">
                                            <input type="hidden" name="insert_project_files" id="insert_project_files">
                                            <input type="hidden" name="key_unique" id="p_key_unique" value="<?= $key_unique ?>">
                                            <input type="hidden" name="project_id" id="project_files" class="project_id" value="<?= $projid ?>">
                                            <input type="hidden" name="files_id" id="files_id" class="files_id" value="<?= $projid != "" ? 1 : "" ?>">
                                            <input type="hidden" name="progid" id="file_progid" value="<?= $progid ?>">
                                            <li><button class="btn btn-success btn-sm" id="project_details_id" type="submit"><?= $projid != "" ? "Edit" : "Save" ?></button></li>
                                        </ul>
                                        <ul class="list-inline pull-right">
                                            <li><button type="button" class="btn btn-warning prev-step">Previous</button></li>
                                            <li><button class="btn btn-primary nextBtn btn-sm" onclick="display_finish()" type="button">Next</button> </li>
                                        </ul>
                                    </div>
                                </form>
                            </fieldset>
                            <fieldset class="scheduler-border row setup-content" id="step-4">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FINISH</legend>
                                <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="row clearfix " id="">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card">
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <fieldset class="scheduler-border">
                                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">1.0) Project Details</legend>
                                                                <div class="table-responsive">
                                                                    <table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">#</th>
                                                                                <th width="35%">Field</th>
                                                                                <th width="60%">Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Programe Name</td>
                                                                                <td id="progs"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Project Code</td>
                                                                                <td id="projcodes"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Project Name</td>
                                                                                <td id="projName"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>6</td>
                                                                                <td>Implementation Method</td>
                                                                                <td id="implementation"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>7</td>
                                                                                <td>Financial Year </td>
                                                                                <td id="projfscyears"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>9</td>
                                                                                <td>Project Duration </td>
                                                                                <td id="projdurations"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>10</td>
                                                                                <td>Evaluation Required?</td>
                                                                                <td id="projeval"></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="table-responsive">
                                                                    <table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="20%"><?= $level1label ?>/s</th>
                                                                                <th width="20%"><?= $level2label ?>/s</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td id="projcommunitys"></td>
                                                                                <td id="projlgas"></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix " id="">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card">
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <fieldset class="scheduler-border">
                                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">4.0) Files</legend>
                                                                <div class="table-responsive">
                                                                    <table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">#</th>
                                                                                <th width="35%">Attachment Purpose</th>
                                                                                <th width="60%">File Name</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="files_attached">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix " id="">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="list-inline text-center">
                                                <input type="hidden" name="MM_insert" value="addprojectfrm">
                                                <input type="hidden" name="username" value="<?= $user_name ?>">
                                                <input type="hidden" name="program_type" value="<?= $program_type ?>">
                                                <input type="hidden" name="planid" value="<?= $planid ?>">
                                                <li><button type="button" class="btn btn-warning prev-step">Previous</button></li>
                                                <li><button class="btn btn-success" id="submit_project" type="submit"><?= $projid != "" ? "Edit" : "Save" ?></button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<!-- validation cdn files  -->
<script>
    const param = '<?= $projevaluation == 1 && $projid != "" ? 1 : 0 ?>';
</script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.js"></script>
<script src="assets/js/projects/index.js"></script>