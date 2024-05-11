<?php
try {
    require('includes/head.php');
    if ($permission) {
        $mbrid = "";
        if (isset($_GET["mbrid"]) && !empty($_GET["mbrid"])) {
            $encoded_mbrid = $_GET["mbrid"];
            $decode_mbrid = base64_decode($encoded_mbrid);
            $mbrid_array = explode("projmbr", $decode_mbrid);
            $mbrid = $mbrid_array[1];
        }

        $workflow_stage_id = 9;

        $query_rsUser = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
        $query_rsUser->execute(array(":user_id" => $mbrid));
        $row_rsUser = $query_rsUser->fetch();
        $count_rsUser = $query_rsUser->rowCount();
        $designation_id = $department_id = $section_id = $directorate_id = '';

        if ($count_rsUser > 0) {
            $designation_id = $row_rsUser['designation'];
            $department_id = $row_rsUser['ministry'];
            $section_id = $row_rsUser['department'];
            $directorate_id = $row_rsUser['directorate'];
            $fullname = $row_rsUser['title'] . ' ' . $row_rsUser['fullname'];
        }

        function get_department_list($project_type)
        {
            global $db, $department_id, $workflow_stage_id;
            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.deleted='0' AND p.projstage = :workflow_stage_id AND g.projsector=:department_id AND p.project_type=:project_type ORDER BY p.projid DESC");
            $query_rsProjects->execute(array(":workflow_stage_id" => $workflow_stage_id, ":department_id" => $department_id, ":project_type" => $project_type));
            $department_projects = $query_rsProjects->rowCount();
            return $department_projects;
        }

        function get_section_list($project_type)
        {
            global $db, $section_id, $workflow_stage_id;
            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.deleted='0' AND p.projstage = :workflow_stage_id AND g.projdept=:section_id AND p.project_type=:project_type ORDER BY p.projid DESC");
            $query_rsProjects->execute(array(":workflow_stage_id" => $workflow_stage_id, ":section_id" => $section_id, ":project_type" => $project_type));
            $section_projects = $query_rsProjects->rowCount();
            return  $section_projects;
        }

        function get_directorate_list($project_type)
        {
            global $db, $directorate_id, $workflow_stage_id;
            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.deleted='0' AND p.projstage = :workflow_stage_id AND g.directorate=:directorate_id  AND p.project_type=:project_type ORDER BY p.projid DESC");
            $query_rsProjects->execute(array(":workflow_stage_id" => $workflow_stage_id, ":directorate_id" => $directorate_id, ":project_type" => $project_type));
            $directorate_projects = $query_rsProjects->rowCount();
            return $directorate_projects;
        }

        function get_project_list($project_type)
        {
            global $db, $mbrid, $designation_id, $department_id, $section_id, $directorate_id, $workflow_stage_id;
            $query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND team_type=4 AND p.project_type=:project_type AND p.projstage=:workflow_stage_id");
            $query_rsNoPrj->execute(array(":responsible" => $mbrid, ":project_type" => $project_type, ":workflow_stage_id" => $workflow_stage_id));
            if ($designation_id == 7) {
                $query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND team_type=4 AND p.project_type=:project_type AND g.projsector<>:department AND p.projstage=:workflow_stage_id");
                $query_rsNoPrj->execute(array(":responsible" => $mbrid, ":project_type" => $project_type, ":department" => $department_id, ":workflow_stage_id" => $workflow_stage_id));
            } else if ($designation_id == 6) {
                $query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND team_type=4 AND p.project_type=:project_type AND g.projdept<>:section AND p.projstage=:workflow_stage_id");
                $query_rsNoPrj->execute(array(":responsible" => $mbrid, ":project_type" => $project_type, ":section" => $section_id, ":workflow_stage_id" => $workflow_stage_id));
            } else if ($designation_id == 5) {
                $query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND team_type=4 AND p.project_type=:project_type AND g.directorate<>:directorate AND p.projstage=:workflow_stage_id");
                $query_rsNoPrj->execute(array(":responsible" => $mbrid, ":project_type" => $project_type, ":directorate" => $directorate_id, ":workflow_stage_id" => $workflow_stage_id));
            }
            $technical_projects = $query_rsNoPrj->rowCount();
            return $technical_projects;
        }

        function get_counter($project_type)
        {
            global  $designation_id;
            $counter = get_project_list($project_type);
            if ($designation_id == 7) {
                $counter +=  get_directorate_list($project_type);
            } else if ($designation_id == 6) {
                $counter += get_section_list($project_type);
            } else if ($designation_id == 5) {
                $counter += get_department_list($project_type);
            }
            return $counter;
        }


        function validate_project($projid, $project_department, $project_section, $project_directorate)
        {
            global $db, $designation_id, $department_id, $section_id, $directorate_id, $mbrid;
            $response = false;
            if ($designation_id == 5) {
                $response = true;
                if ($department_id != $project_department) {
                    $query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND team_type=4 AND m.projid=:projid");
                    $query_rsNoPrj->execute(array(":responsible" => $mbrid, ":projid" => $projid));
                    $technical_projects = $query_rsNoPrj->rowCount();
                    $response = $technical_projects > 0 ? true : false;
                }
            } else if ($designation_id == 6) {
                $response = true;
                if ($section_id != $project_section) {
                    $query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND team_type=4 AND m.projid=:projid ");
                    $query_rsNoPrj->execute(array(":responsible" => $mbrid, ":projid" => $projid));
                    $technical_projects = $query_rsNoPrj->rowCount();
                    $response = $technical_projects > 0 ? true : false;
                }
            } else if ($designation_id == 7) {
                $response = true;
                if ($directorate_id != $project_directorate) {
                    $query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND team_type=4 AND m.projid=:projid");
                    $query_rsNoPrj->execute(array(":responsible" => $mbrid, ":projid" => $projid));
                    $technical_projects = $query_rsNoPrj->rowCount();
                    $response = $technical_projects > 0 ? true : false;
                }
            } else {
                $query_rsNoPrj = $db->prepare("SELECT m.projid FROM tbl_projmembers m INNER JOIN tbl_projects p ON p.projid=m.projid INNER JOIN tbl_programs g ON g.progid=p.progid  WHERE responsible=:responsible AND team_type=4 AND m.projid=:projid");
                $query_rsNoPrj->execute(array(":responsible" => $mbrid, ":projid" => $projid));
                $technical_projects = $query_rsNoPrj->rowCount();
                $response = $technical_projects > 0 ? true : false;
            }
            return $response;
        }

        $query_rs_sp_projects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted = '0'  AND p.project_type=1 AND p.projstage =10");
        $query_rs_sp_projects->execute();
        $total_sp_projects = $query_rs_sp_projects->rowCount();

        $query_rs_ind_projects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted = '0' AND p.project_type=0 AND p.projstage =10");
        $query_rs_ind_projects->execute();
        $total_ind_projects = $query_rs_ind_projects->rowCount();

?>
        <style>
            .modal-lg {
                max-width: 100% !important;
                width: 90%;
            }
        </style>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon  . ' ' . $fullname . ' ' . $pageTitle ?>
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
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#home">
                                        <i class="fa fa-caret-square-o-down bg-green" aria-hidden="true"></i> Strategic Plan Projects &nbsp;

                                        <span class="badge bg-green"><?= get_counter(1) ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1">
                                        <i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Independent Projects &nbsp;
                                        <span class="badge bg-blue"><?= get_counter(0) ?></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="body">
                                <!-- strat body -->
                                <div class="tab-content">
                                    <div id="home" class="tab-pane fade in active">
                                        <div class="body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                        <tr id="colrow">
                                                            <th width="4%"><strong>SN</strong></th>
                                                            <th width="28%"><strong>Project</strong></th>
                                                            <th width="12%"><strong><?= $departmentlabel ?></strong></th>
                                                            <th width="10%"><strong>Status & Progress(%)</strong></th>
                                                            <th width="7%"><strong>Issues</strong></th>
                                                            <th width="9%"><strong>Location</strong></th>
                                                            <th width="10%"><strong>Fiscal Year</strong></th>
                                                            <th width="10%"><strong>Last Update</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- =========================================== -->
                                                        <?php


                                                        if ($total_sp_projects > 0) {
                                                            $sn = 0;
                                                            while ($row__sp_projects = $query_rs_sp_projects->fetch()) {
                                                                $projid =  $row__sp_projects['projid'];
                                                                $projname =  $row__sp_projects['projname'];
                                                                $progid =  $row__sp_projects['progid'];
                                                                $projcode =  $row__sp_projects['projcode'];
                                                                $projcost =  $row__sp_projects['projcost'];
                                                                $projstage =  $row__sp_projects['projstage'];
                                                                $projstatus =  $row__sp_projects['projstatus'];
                                                                $location = explode(",", $row__sp_projects['projlga']);
                                                                $fscyear = $row__sp_projects['projfscyear'];
                                                                $row_progid = $row__sp_projects['progid'];
                                                                $project_start_date =  $row__sp_projects['projstartdate'];
                                                                $project_end_date =  $row__sp_projects['projenddate'];
                                                                $projcategory =  $row__sp_projects['projcategory'];
                                                                $percent2 =  $row__sp_projects['progress'];

                                                                $project_department =  $row__sp_projects['projsector'];
                                                                $project_directorate =  $row__sp_projects['projdept'];
                                                                $project_section =  $row__sp_projects['directorate'];




                                                                $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid LIMIT 1");
                                                                $query_rsTask_Start_Dates->execute(array(':projid' => $projid));
                                                                $rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                                $total_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

                                                                if (!is_null($rows_rsTask_Start_Dates['start_date'])) {
                                                                    $project_start_date =  $rows_rsTask_Start_Dates['start_date'];
                                                                    $project_end_date =  $rows_rsTask_Start_Dates['end_date'];
                                                                } else {
                                                                    if ($projcategory == 2) {
                                                                        $query_rsTender_start_Date = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
                                                                        $query_rsTender_start_Date->execute(array(':projid' => $projid));
                                                                        $rows_rsTender_start_Date = $query_rsTender_start_Date->fetch();
                                                                        $total_rsTender_start_Date = $query_rsTender_start_Date->rowCount();
                                                                        if ($total_rsTender_start_Date > 0) {
                                                                            $project_start_date =  $rows_rsTender_start_Date['startdate'];
                                                                            $project_end_date =  $rows_rsTender_start_Date['enddate'];
                                                                        }
                                                                    }
                                                                }

                                                                $query_rsSect = $db->prepare("SELECT sector FROM tbl_sectors s inner join tbl_programs g on g.projsector = s.stid WHERE progid=:progid");
                                                                $query_rsSect->execute(array(":progid" => $progid));
                                                                $row_rsSector = $query_rsSect->fetch();
                                                                $totalRows_rsSect = $query_rsSect->rowCount();

                                                                $sector = $totalRows_rsSect > 0 ? $row_rsSector['sector'] : "";

                                                                $query_FY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id=:fscyear");
                                                                $query_FY->execute(array(":fscyear" => $fscyear));
                                                                $row_FY = $query_FY->fetch();
                                                                $totalRows_rsFY = $query_FY->rowCount();
                                                                $financial_year = $totalRows_rsFY > 0 ? $row_FY['year'] : "";

                                                                $query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid");
                                                                $query_rsProjissues->execute(array(":projid" => $projid));
                                                                $totalRows_rsProjissues = $query_rsProjissues->rowCount();

                                                                $query_dates = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name, contrid FROM tbl_projects LEFT JOIN tbl_contractor ON tbl_projects.projcontractor = tbl_contractor.contrid WHERE projid=:projid");
                                                                $query_dates->execute(array(":projid" => $projid));
                                                                $row_dates = $query_dates->fetch();

                                                                $projcontractor = "In House";
                                                                if ($row_dates['projcategory'] == 2) {
                                                                    $contractor = $row_dates['contractor_name'];
                                                                    $projcontractor_id = $row_dates['contrid'];
                                                                    $projcontractor =  '<a href="view-project-contractor-info?contrid=' . $projcontractor_id . '" style="color:#4CAF50">' . $contractor . '</a>';
                                                                }


                                                                $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                                                                $query_Projstatus->execute(array(":projstatus" => $projstatus));
                                                                $row_Projstatus = $query_Projstatus->fetch();
                                                                $total_Projstatus = $query_Projstatus->rowCount();
                                                                $status = "";
                                                                if ($total_Projstatus > 0) {
                                                                    $status_name = $row_Projstatus['statusname'];
                                                                    $status_class = $row_Projstatus['class_name'];
                                                                    $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
                                                                }

                                                                $project_progress = '
                                                            <div class="progress" style="height:20px; font-size:10px; color:black">
                                                                <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
                                                                    ' . $percent2 . '%
                                                                </div>
                                                            </div>';
                                                                if ($percent2 == 100) {
                                                                    $project_progress = '
                                                                <div class="progress" style="height:20px; font-size:10px; color:black">
                                                                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
                                                                    ' . $percent2 . '%
                                                                    </div>
                                                                </div>';
                                                                }

                                                                $locations = [];
                                                                foreach ($location as $mystate) {
                                                                    $query_rsLoc = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:mystate");
                                                                    $query_rsLoc->execute(array(":mystate" => $mystate));
                                                                    $row_rsLoc = $query_rsLoc->fetch();
                                                                    $totalRows_rsLoc = $query_rsLoc->rowCount();
                                                                    $locations[] = $row_rsLoc['state'];
                                                                }

                                                                $query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE projid=:projid  ORDER BY id DESC LIMIT 1");
                                                                $query_rsMonitoring_Achieved->execute(array(":projid" => $projid));
                                                                $Rows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->fetch();
                                                                $totalRows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->rowCount();

                                                                $projlastmn = $totalRows_rsMonitoring_Achieved > 0 ? date("d M Y", strtotime($Rows_rsMonitoring_Achieved['created_at'])) : '';

                                                                if (validate_project($projid, $project_department, $project_section, $project_directorate)) {
                                                                    $sn++;
                                                        ?>
                                                                    <tr id="rows">
                                                                        <td><?php echo $sn; ?></td>
                                                                        <td style="padding-right:0px; padding-left:0px; padding-top:0px">
                                                                            <div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
                                                                                <p style="color:#FFF; font-weight:bold"><?= $projname ?> </p>
                                                                            </div>
                                                                            <div style="padding:5px; font-size:11px">
                                                                                <b>Project Code:</b> <?= $projcode ?><br />
                                                                                <b>Project Cost:</b> Ksh.<?= $projcost; ?><br />
                                                                                <b>Start Date:</b> <?= $project_start_date; ?><br />
                                                                                <b>End Date: </b> <?= $project_end_date; ?><br />
                                                                                <b>Implementer: </b>
                                                                                <font color="#4CAF50">
                                                                                    <?= $projcontractor; ?>
                                                                                </font>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= $sector ?></td>
                                                                        <td style="padding-right:0px; padding-left:0px">
                                                                            <?= $status  ?>
                                                                            <br />
                                                                            <strong>
                                                                                <?= $project_progress ?>
                                                                            </strong>
                                                                            <br />
                                                                        </td>
                                                                        <td align="center">
                                                                            <a href="#" onclick="javascript:GetProjIssues(<?= $projid ?>)" style="color:#FF5722">
                                                                                <i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i>
                                                                                <font size="5px"><?= $totalRows_rsProjissues ?></font>
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            <?= implode(",", $locations); ?>
                                                                        </td>
                                                                        <td><?= $financial_year ?></td>
                                                                        <td><?php echo $projlastmn; ?></td>
                                                                    </tr>
                                                        <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="menu1" class="tab-pane fade">
                                        <div class="body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                        <tr id="colrow">
                                                            <th width="4%"><strong>SN</strong></th>
                                                            <th width="28%"><strong>Project</strong></th>
                                                            <th width="12%"><strong><?= $departmentlabel ?></strong></th>
                                                            <th width="10%"><strong>Status & Progress(%)</strong></th>
                                                            <th width="7%"><strong>Issues</strong></th>
                                                            <th width="9%"><strong>Location</strong></th>
                                                            <th width="10%"><strong>Fiscal Year</strong></th>
                                                            <th width="10%"><strong>Last Update</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- =========================================== -->
                                                        <?php
                                                        if ($total_ind_projects > 0) {
                                                            $sn = 0;
                                                            while ($row__ind_projects = $query_rs_ind_projects->fetch()) {
                                                                $projid =  $row__ind_projects['projid'];
                                                                $projname =  $row__ind_projects['projname'];
                                                                $progid =  $row__ind_projects['progid'];
                                                                $projcode =  $row__ind_projects['projcode'];
                                                                $projcost =  $row__ind_projects['projcost'];
                                                                $projstage =  $row__ind_projects['projstage'];
                                                                $projstatus =  $row__ind_projects['projstatus'];
                                                                $location = explode(",", $row__ind_projects['projlga']);
                                                                $fscyear = $row__ind_projects['projfscyear'];
                                                                $row_progid = $row__ind_projects['progid'];
                                                                $project_start_date =  $row__ind_projects['projstartdate'];
                                                                $project_end_date =  $row__ind_projects['projenddate'];
                                                                $projcategory =  $row__ind_projects['projcategory'];
                                                                $percent2 = $row__ind_projects['progress'];
                                                                $project_department =  $row__ind_projects['projsector'];
                                                                $project_directorate =  $row__ind_projects['projdept'];
                                                                $project_section =  $row__ind_projects['directorate'];

                                                                $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid LIMIT 1");
                                                                $query_rsTask_Start_Dates->execute(array(':projid' => $projid));
                                                                $rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                                $total_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

                                                                if (!is_null($rows_rsTask_Start_Dates['start_date'])) {
                                                                    $project_start_date =  $rows_rsTask_Start_Dates['start_date'];
                                                                    $project_end_date =  $rows_rsTask_Start_Dates['end_date'];
                                                                } else {
                                                                    if ($projcategory == 2) {
                                                                        $query_rsTender_start_Date = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
                                                                        $query_rsTender_start_Date->execute(array(':projid' => $projid));
                                                                        $rows_rsTender_start_Date = $query_rsTender_start_Date->fetch();
                                                                        $total_rsTender_start_Date = $query_rsTender_start_Date->rowCount();
                                                                        if ($total_rsTender_start_Date > 0) {
                                                                            $project_start_date =  $rows_rsTender_start_Date['startdate'];
                                                                            $project_end_date =  $rows_rsTender_start_Date['enddate'];
                                                                        }
                                                                    }
                                                                }

                                                                $query_rsSect = $db->prepare("SELECT sector FROM tbl_sectors s inner join tbl_programs g on g.projsector = s.stid WHERE progid=:progid");
                                                                $query_rsSect->execute(array(":progid" => $progid));
                                                                $row_rsSector = $query_rsSect->fetch();
                                                                $totalRows_rsSect = $query_rsSect->rowCount();

                                                                $sector = $totalRows_rsSect > 0 ? $row_rsSector['sector'] : "";

                                                                $query_FY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id=:fscyear");
                                                                $query_FY->execute(array(":fscyear" => $fscyear));
                                                                $row_FY = $query_FY->fetch();
                                                                $totalRows_rsFY = $query_FY->rowCount();
                                                                $financial_year = $totalRows_rsFY > 0 ? $row_FY['year'] : "";

                                                                $query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid");
                                                                $query_rsProjissues->execute(array(":projid" => $projid));
                                                                $totalRows_rsProjissues = $query_rsProjissues->rowCount();

                                                                $query_dates = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name, contrid FROM tbl_projects LEFT JOIN tbl_contractor ON tbl_projects.projcontractor = tbl_contractor.contrid WHERE projid=:projid");
                                                                $query_dates->execute(array(":projid" => $projid));
                                                                $row_dates = $query_dates->fetch();

                                                                $projcontractor = "In House";
                                                                if ($row_dates['projcategory'] == 2) {
                                                                    $contractor = $row_dates['contractor_name'];
                                                                    $projcontractor_id = $row_dates['contrid'];
                                                                    $projcontractor =  '<a href="view-project-contractor-info?contrid=' . $projcontractor_id . '" style="color:#4CAF50">' . $contractor . '</a>';
                                                                }

                                                                $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                                                                $query_Projstatus->execute(array(":projstatus" => $projstatus));
                                                                $row_Projstatus = $query_Projstatus->fetch();
                                                                $total_Projstatus = $query_Projstatus->rowCount();
                                                                $status = "";
                                                                if ($total_Projstatus > 0) {
                                                                    $status_name = $row_Projstatus['statusname'];
                                                                    $status_class = $row_Projstatus['class_name'];
                                                                    $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
                                                                }


                                                                $project_progress = '
                                                            <div class="progress" style="height:20px; font-size:10px; color:black">
                                                                <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
                                                                    ' . $percent2 . '%
                                                                </div>
                                                            </div>';
                                                                if ($percent2 == 100) {
                                                                    $project_progress = '
                                                                <div class="progress" style="height:20px; font-size:10px; color:black">
                                                                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
                                                                    ' . $percent2 . '%
                                                                    </div>
                                                                </div>';
                                                                }


                                                                $locations = [];
                                                                foreach ($location as $mystate) {
                                                                    $query_rsLoc = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:mystate");
                                                                    $query_rsLoc->execute(array(":mystate" => $mystate));
                                                                    $row_rsLoc = $query_rsLoc->fetch();
                                                                    $totalRows_rsLoc = $query_rsLoc->rowCount();
                                                                    $locations[] = $row_rsLoc['state'];
                                                                }

                                                                $query_rsMonitoring_Achieved = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE projid=:projid  ORDER BY id DESC LIMIT 1");
                                                                $query_rsMonitoring_Achieved->execute(array(":projid" => $projid));
                                                                $Rows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->fetch();
                                                                $totalRows_rsMonitoring_Achieved = $query_rsMonitoring_Achieved->rowCount();

                                                                $projlastmn = $totalRows_rsMonitoring_Achieved > 0 ? date("d M Y", strtotime($Rows_rsMonitoring_Achieved['created_at'])) : '';

                                                                if (validate_project($projid, $project_department, $project_section, $project_directorate)) {
                                                                    $sn++;

                                                        ?>
                                                                    <tr id="rows">
                                                                        <td><?php echo $sn; ?></td>
                                                                        <td style="padding-right:0px; padding-left:0px; padding-top:0px">
                                                                            <div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
                                                                                <p style="color:#FFF; font-weight:bold"><?= $projname ?> </p>
                                                                            </div>
                                                                            <div style="padding:5px; font-size:11px">
                                                                                <b>Project Code:</b> <?= $projcode ?><br />
                                                                                <b>Project Cost:</b> Ksh.<?= $projcost; ?><br />
                                                                                <b>Start Date:</b> <?= $project_start_date; ?><br />
                                                                                <b>End Date: </b> <?= $project_end_date; ?><br />
                                                                                <b>Implementer: </b>
                                                                                <font color="#4CAF50">
                                                                                    <?= $projcontractor; ?>
                                                                                </font>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= $sector ?></td>
                                                                        <td style="padding-right:0px; padding-left:0px">
                                                                            <?= $status  ?>
                                                                            <br />
                                                                            <strong>
                                                                                <?= $project_progress ?>
                                                                            </strong>
                                                                            <br />
                                                                        </td>
                                                                        <td align="center">
                                                                            <a href="#" onclick="javascript:GetProjIssues(<?= $projid ?>)" style="color:#FF5722">
                                                                                <i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i>
                                                                                <font size="5px"><?= $totalRows_rsProjissues ?></font>
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            <?= implode(",", $locations); ?>
                                                                        </td>
                                                                        <td><?= $financial_year ?></td>
                                                                        <td><?php echo $projlastmn; ?></td>
                                                                    </tr>
                                                        <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end body  -->

        <!-- Modal Scorecard-->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                            <font color="#000000">Project Scorecard</font>
                        </h4>
                    </div>
                    <div class="modal-body" id="formcontent">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Project Details-->
        <div class="modal fade" id="projDetails" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">
                            <font color="#000000">Project Details</font>
                        </h3>
                    </div>
                    <div class="modal-body" id="detailscontent">
                    </div>
                    <div class="modal-footer">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="projectModal" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <h4 class="modal-title new-title" ALIGN="center" style="color:#FFF">Modal Title</h4>
                    </div>
                    <div class="modal-body">
                        <div id="map"></div>
                        <div id="photo"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Project Issues -->
        <div class="modal fade" id="projIssues" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title" align="center" style="color:#FF5722; font-size:24px">Project Issues</h2>
                    </div>
                    <div class="modal-body" id="issuescontent">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        $results =  restriction();
        echo $results;
    }
    require('includes/footer.php');
} catch (PDOException $ex) {
    var_dump($ex);
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>