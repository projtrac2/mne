<?php
try {

require('includes/head.php');
if ($permission) {
    try {
        $program_type = $planid = $strategic_plan_program_id = $progid = $projid = $projcode = $projname = $projdescription = $projtype = $projendyear = "";
        $projbudget  = $projduration = $projevaluation = $projimpact  = $projimpact = $asset = "";
        $project_budget = 0;
        $projcommunity = $projlga = $projlocation = $project_type = "";
        $projcategory = $projstatus = "";
        $program_name  = "";

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
                $strategic_plan_program_id = $row_rsProgjects['strategic_plan_program_id'];
                $projcode = $row_rsProgjects['projcode'];
                $projname = $row_rsProgjects['projname'];
                $projdescription = $row_rsProgjects['projdesc'];
                $projtype = $row_rsProgjects['projtype'];
                $projbudget = $row_rsProgjects['projbudget'];
                $projduration = $row_rsProgjects['projduration'];
                $projevaluation = $row_rsProgjects['projevaluation'];
                $projcommunity = $row_rsProgjects['projcommunity'];
                $projlga = $row_rsProgjects['projlga'];
                $projlocation = $row_rsProgjects['projlocation'];
                $projcategory = $row_rsProgjects['projcategory'];
                $projimpact = $row_rsProgjects['projimpact'];
                $project_budget = $row_rsProgjects['projcost'];
                $asset = $row_rsProgjects['asset'];
                $project_type = $row_rsProgjects['project_type'];
            }
        } else if (isset($_GET['progid'])) {
            $decode_progid = (isset($_GET['progid']) && !empty($_GET["progid"])) ? base64_decode($_GET['progid']) : "";
            $progid_array = explode("progid54321", $decode_progid);
            $strategic_plan_program_id = $progid_array[1];
        }

        $query_rsBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_progdetails WHERE progid=:progid");
        $query_rsBudget->execute(array(":progid" => $progid));
        $row_rsPBudget = $query_rsBudget->fetch();
        $programs_budget = $row_rsPBudget['budget'] != null ? $row_rsPBudget['budget'] : 0;

        $query_rsProjectBudget = $db->prepare("SELECT  SUM(projcost) AS projcost FROM tbl_projects WHERE progid=:progid");
        $query_rsProjectBudget->execute(array(":progid" => $progid));
        $row_rsProjectBudget = $query_rsProjectBudget->fetch();
        $project_program_budget = $row_rsProjectBudget['projcost'] != null  ? $row_rsProjectBudget['projcost'] : 0;
        $program_budget = ($programs_budget - $project_program_budget) + $project_budget;


        $query_rsProgram = $db->prepare("SELECT * FROM tbl_programs g INNER JOIN tbl_strategic_plan_programs s ON  s.progid=g.progid WHERE id=:strategic_plan_program_id");
        $query_rsProgram->execute(array(":strategic_plan_program_id" => $strategic_plan_program_id));
        $row_rsProgram = $query_rsProgram->fetch();
        $totalRows_rsProgram = $query_rsProgram->rowCount();

        $strategic_plan_id = '';
        if ($totalRows_rsProgram > 0) {
            $progid = $row_rsProgram['progid'];
            $program_name = $row_rsProgram['progname'];
            $strategic_plan_id = $row_rsProgram['strategic_plan_id'];
        }

        $redirect_url = "strategic-plan-projects?plan=" . base64_encode("strplan1{$strategic_plan_id}");

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

        $stage = 0;
        $query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projstage=:stage and projid=:projid");
        $query_rsFile->execute(array(":stage" => $stage, ":projid" => $projid));
        $row_rsFile = $query_rsFile->fetch();
        $totalRows_rsFile = $query_rsFile->rowCount();

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
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item list-group-item-action active">Program: <?= $program_name ?> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                                <fieldset class="scheduler-border" style="padding:10px">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ADD PROJECT DETAILS</legend>
                                    <form role="form" id="project_details" action="" method="post" autocomplete="off" enctype="multipart/form-data">
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
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label for="projduration">Project Duration (Days) *:</label>
                                            <div class="form-input">
                                                <input type="number" name="projduration1" min="0" value="<?= $projduration ?>" id="projduration1" placeholder="Enter" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label for="project_budget">Project Budget *:<span class="text-danger">(Ksh. <?= number_format($program_budget) ?>)</span></label>
                                            <input type="number" name="project_budget" min="1" id="project_budget" value="<?= $project_budget ?>" onchange="calculate_project_budget()" onkeyup="calculate_project_budget()" class="form-control" required>
                                            <input type="hidden" name="program_budget_ceiling" id="program_budget_hidden" value="<?= $program_budget ?>">
                                            <span id="" style="color:red"></span>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label class="control-label">Implementation Method *:</label>
                                            <div class="form-line">
                                                <select name="projimplmethod" id="projimplmethod" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">.... Select the method ....</option>
                                                    <?= get_implimentation_method($projcategory) ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label for="" class="control-label">SP ? *:</label>
                                            <div class="form-line">
                                                <input name="project_type" type="radio" value="1" <?= $project_type == 1 && $projid != "" ? "checked" : "" ?> id="project_type1" class="with-gap radio-col-green project_type" required="required" />
                                                <label for="project_type1">YES</label>
                                                <input name="project_type" type="radio" value="0" <?= $project_type == 0 && $projid != "" ? "checked" : "" ?> id="project_type2" class="with-gap radio-col-red project_type" required="required" />
                                                <label for="project_type2">NO</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label for="" class="control-label">Outcome Evaluation Required? *:</label>
                                            <div class="form-line">
                                                <input name="projevaluation" type="radio" value="1" onchange="show_impact(1)" <?= $projevaluation == 1 && $projid != "" ? "checked" : "" ?> id="evaluation1" class="with-gap radio-col-green evaluation" required="required" />
                                                <label for="evaluation1">YES</label>
                                                <input name="projevaluation" type="radio" value="0" onchange="show_impact(0)" <?= $projevaluation == 0 && $projid != "" ? "checked" : "" ?> id="evaluation2" class="with-gap radio-col-red evaluation" required="required" />
                                                <label for="evaluation2">NO</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="impact_div">
                                            <label for="" class="control-label">Impact Evaluation Required? *:</label>
                                            <div class="form-line">
                                                <input name="impact" type="radio" value="1" id="impact1" <?= $projimpact == 1 && $projid != "" ? "checked" : "" ?> class="with-gap radio-col-green impact" />
                                                <label for="impact1">YES</label>
                                                <input name="impact" type="radio" value="0" id="impact2" <?= $projimpact == 0 && $projid != "" ? "checked" : "" ?> class="with-gap radio-col-red impact" />
                                                <label for="impact2">NO</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <label for="" class="control-label">Asset ? *:</label>
                                            <div class="form-line">
                                                <input name="project_asset" type="radio" value="1" <?= $asset == 1 && $projid != "" ? "checked" : "" ?> id="project_asset1" class="with-gap radio-col-green project_asset" required="required" />
                                                <label for="project_asset1">YES</label>
                                                <input name="project_asset" type="radio" value="0" <?= $asset == 0 && $projid != "" ? "checked" : "" ?> id="project_asset2" class="with-gap radio-col-red project_asset" required="required" />
                                                <label for="project_asset2">NO</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="" class="control-label">Project Sites Required? *:</label>
                                            <div class="form-line">
                                                <input name="project_sites" type="radio" value="1" onchange="hide_project_site_table(1)" <?= $totalRows_rsSites > 0 && $projid != "" ? "checked" : "" ?> id="project_sites1" class="with-gap radio-col-green project_site" required="required" />
                                                <label for="project_sites1">YES</label>
                                                <input name="project_sites" type="radio" value="0" onchange="hide_project_site_table(0)" <?= $totalRows_rsSites == 0 && $projid != "" ? "checked" : "" ?> id="project_sites2" class="with-gap radio-col-red project_site" required="required" />
                                                <label for="project_sites2">NO</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        </div>
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
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                        </div>

                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FILES</legend>
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
                                        </fieldset>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="list-inline text-center">
                                                <li>
                                                    <input type="hidden" name="project_id" id="project_id" value="<?= $projid != '' ? $projid : ''; ?>">
                                                    <input type="hidden" name="insert_project" value="insert_project">
                                                    <input type="hidden" name="progid" value="<?= $progid ?>">
                                                    <input type="hidden" name="redirect_url" id="redirect_url" value="<?= $redirect_url ?>">
                                                    <input type="hidden" name="strategic_plan_program_id" value="<?= $strategic_plan_program_id ?>">
                                                    <button class="btn btn-success btn-sm" id="project_details_id" type="submit">
                                                        <?= $totalRows_rsSites > 0 ? "Edit" : "Save" ?>
                                                    </button>
                                                </li>
                                            </ul>
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
    } catch (PDOException $ex) {
        var_dump($ex);
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
} else {
    $results =  restriction();
    echo $results;
}
} catch (PDOException $ex) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
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