<?php
try {
    require('includes/head.php');
    if ($permission && (isset($_GET['stage']) && !empty($_GET["stage"]))) {
        $decode_stage =   base64_decode($_GET['stage']);
        $stage_array = explode("projid54321", $decode_stage);
        $stage = $stage_array[1];

        $decode_projid =  base64_decode($_GET['stage']);
        $projid_array = explode("projid54321", $decode_projid);
        $projid_stage = $projid_array[1];

        $access_level = "";
        if (($user_designation < 5)) {
            $access_level = "";
        } elseif ($user_designation == 5) {
            $access_level = " AND g.projsector=$user_department";
        } elseif ($user_designation == 6) {
            $access_level = " AND g.projsector=$user_department AND g.projdept=$user_section";
        } elseif ($user_designation > 6) {
            $access_level = " AND g.projsector=$user_department AND g.projdept=$user_section AND g.directorate=$user_directorate";
        }


        function widgets($stage_id, $level_one_id, $level_two_id, $project_type)
        {
            global $db, $access_level;
            $query_rsprojects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.stage_id=:stage_id AND project_type=:project_type $access_level ");
            $query_rsprojects->execute(array(":stage_id" => $stage_id, ":project_type" => $project_type));
            $allprojects = $query_rsprojects->rowCount();
            $projids_array = [];
            if ($allprojects > 0) {
                while ($all_projects = $query_rsprojects->fetch()) {
                    $projid = $all_projects['projid'];
                    $level_one_ids = explode(",", $all_projects['projcommunity']);
                    if ($level_one_id != '') {
                        if ($level_two_id != '') {
                            $level_two_ids = explode(",", $all_projects['projlga']);
                            if (in_array($level_two_id, $level_two_ids)) {
                                $projids_array[] = $projid;
                            }
                        } else {
                            if (in_array($level_one_id, $level_one_ids)) {
                                $projids_array[] = $projid;
                            }
                        }
                    } else {
                        $projids_array[] = $projid;
                    }
                }
            }

            return $projids_array;
        }

        function get_wards($location)
        {
            global $db;
            $locations = [];
            foreach ($location as $mystate) {
                $query_rsLoc = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:mystate");
                $query_rsLoc->execute(array(":mystate" => $mystate));
                $row_rsLoc = $query_rsLoc->fetch();
                $totalRows_rsLoc = $query_rsLoc->rowCount();
                $locations[] = $totalRows_rsLoc > 0 ? $row_rsLoc['state'] : '';
            }
            return implode(",", $locations);
        }

        function get_sector($progid)
        {
            global $db;
            $query_rsSect = $db->prepare("SELECT sector FROM tbl_sectors s inner join tbl_programs g on g.projsector = s.stid WHERE progid=:progid");
            $query_rsSect->execute(array(":progid" => $progid));
            $row_rsSector = $query_rsSect->fetch();
            $totalRows_rsSect = $query_rsSect->rowCount();
            return $totalRows_rsSect > 0 ? $row_rsSector['sector'] : "";
        }

        $projects = $ind_projects = [];
        $projects =  widgets($stage, '', '', 1);
        $ind_projects =  widgets($stage, '', '', 0);
        if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
            $level_one_id = isset($_GET['projscounty']) && !empty($_GET['projscounty']) ? $_GET['projscounty'] : '';
            $level_two_id = isset($_GET['projward']) && !empty($_GET['projward']) ? $_GET['projward'] : '';
            $stage = (isset($_GET['stage']))  ? $_GET['stage'] : '';
            $projects =  widgets($stage, $level_one_id, $level_two_id, 1);
            $ind_projects =  widgets($stage, $level_one_id, $level_two_id, 0);
        }
        $_SESSION['back_url'] = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon  . ' ' . get_project_stage($stage) . " Projects"  ?>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group" style="float:right">
                                <button onclick="location.href='dashboard.php'" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">
                                    Go Back
                                </button>
                            </div>
                        </div>
                    </h4>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#home">
                                        <i class="fa fa-caret-square-o-down bg-green" aria-hidden="true"></i> Strategic Plan Projects &nbsp;
                                        <span class="badge bg-green"><?= count($projects) ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1">
                                        <i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Independent Projects &nbsp;
                                        <span class="badge bg-blue"><?= count($ind_projects) ?></span>
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
                                                            <th width="25%"><strong>Project &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></th>
                                                            <th width="12%"><strong><?= $departmentlabel ?></strong></th>
                                                            <th width="8%"><strong>Budget (Ksh)</strong></th>
                                                            <?php
                                                            if ($stage > 2) {
                                                            ?>
                                                                <th width="8%"><strong>Start Date</strong></th>
                                                                <th width="8%"><strong>End Date</strong></th>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <th width="16%"><strong>Duration (Days)</strong></th>
                                                            <?php
                                                            }
                                                            if ($stage == 2) {
                                                            ?>
                                                                <th width="10%"><strong>Substage,Status,Progress(%)</strong></th>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <th width="10%"><strong>Substage,Status</strong></th>
                                                            <?php
                                                            }
                                                            ?>
                                                            <th width="9%"><strong> <?= $level2label ?></strong></th>
                                                            <th width="7%"><strong>Issues</strong></th>
                                                            <th width="9%"><strong>Implementer</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- =========================================== -->
                                                        <?php
                                                        if (count($projects) > 0) {
                                                            $sn = 0;
                                                            foreach ($projects as $projid) {
                                                                $sn++;
                                                                $query_rsprojects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
                                                                $query_rsprojects->execute(array(":projid" => $projid));
                                                                $detail = $query_rsprojects->fetch();
                                                                $allprojects = $query_rsprojects->rowCount();
                                                                $projname =  $detail['projname'];
                                                                $progid =  $detail['progid'];
                                                                $projcode =  $detail['projcode'];
                                                                $projcost =  $detail['projcost'];
                                                                $projstage =  $detail['projstage'];
                                                                $substage_id =  $detail['proj_substage'];
                                                                $projstatus =  $detail['projstatus'];
                                                                $duration =  $detail['projduration'];
                                                                $location = explode(",", $detail['projlga']);
                                                                $fscyear = $detail['projfscyear'];
                                                                $row_progid = $detail['progid'];
                                                                $projcategory =  $detail['projcategory'];
                                                                $progress = number_format(calculate_project_progress($projid, $projcategory), 2);
                                                                $projectid = base64_encode("projid54321{$projid}");

                                                                $project_start_date = '';
                                                                $project_end_date = '';
                                                                if ($stage > 2) {
                                                                    $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid LIMIT 1");
                                                                    $query_rsTask_Start_Dates->execute(array(':projid' => $projid));
                                                                    $rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                                    $total_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                                    if ($total_rsTask_Start_Dates > 0) {
                                                                        $project_start_date =  date_format(date_create($rows_rsTask_Start_Dates['start_date']), "d M Y");
                                                                        $project_end_date =  date_format(date_create($rows_rsTask_Start_Dates['end_date']), "d M Y");
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

                                                                $projcontractor = "In House";
                                                                if ($projcategory == 2) {
                                                                    $projcontractor = "Contractor";
                                                                    $query_contractor = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name, contrid FROM tbl_projects p LEFT JOIN tbl_contractor c ON p.projcontractor = c.contrid WHERE projid=:projid");
                                                                    $query_contractor->execute(array(":projid" => $projid));
                                                                    $row_contractor = $query_contractor->fetch();
                                                                    $totalRows_contractor = $query_contractor->rowCount();

                                                                    if ($totalRows_contractor > 0) {
                                                                        $contractor = $row_contractor['contractor_name'];
                                                                        $projcontractor_id = $row_contractor['contrid'];
                                                                        $projcontractor_ids = base64_encode("projid54321{$projcontractor_id}");
                                                                        $projcontractor =  '<a href="view-project-contractor-info?contrid=' . $projcontractor_ids . '" style="color:#4CAF50">' . $contractor . '</a>';
                                                                    }
                                                                }

                                                                $project_progress = get_project_progress($progress);
                                                                $status = get_project_status($projstatus);
                                                                $locations = get_wards($location);
                                                                $project_stage =  get_project_stage($projstage);
                                                        ?>
                                                                <tr id="rows">
                                                                    <td width="4%"><?php echo $sn; ?></td>
                                                                    <td width="25%" style="padding-right:0px; padding-left:0px; padding-top:0px">
                                                                        <div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
                                                                            <b>Code:</b> <?= $projcode ?><br />
                                                                            <b>Name:</b> <a href="project-dashboard?proj=<?= $projectid ?>" style="color:#FFF; font-weight:bold"><?= $projname ?></a>
                                                                        </div>
                                                                    </td>
                                                                    <td width="12%"><?= $sector ?></td>
                                                                    <td width="8%"><?= number_format($projcost, 2) ?></td>
                                                                    <?php
                                                                    if ($stage > 2) {
                                                                    ?>
                                                                        <td width="8%"><?= $project_start_date; ?></td>
                                                                        <td width="8%"><?= $project_end_date; ?></td>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <td width="8%"><?= $duration; ?></td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <td width="10%" style="padding-right:0px; padding-left:0px">
                                                                        <br />
                                                                        <br />
                                                                        <?= "Sub-Stage: " . $project_stage . " <br/>" . $status  ?>
                                                                        <br />
                                                                        <?php
                                                                        if ($stage == 2) {
                                                                        ?>
                                                                            <strong>
                                                                                <?= $project_progress ?>
                                                                            </strong>
                                                                            <br />
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <?= $locations; ?>
                                                                    </td>
                                                                    <td width="7%" align="center">
                                                                        <a href="#" onclick="javascript:GetProjIssues(<?= $projid ?>)" style="color:#FF5722">
                                                                            <i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i>
                                                                            <font size="5px"><?= $totalRows_rsProjissues ?></font>
                                                                        </a>
                                                                    </td>
                                                                    <td width="9%"><?php echo $projcontractor; ?></td>
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
                                    <div id="menu1" class="tab-pane fade">
                                        <div class="body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                        <tr id="colrow">
                                                            <th width="4%"><strong>SN</strong></th>
                                                            <th width="25%"><strong>Project</strong></th>
                                                            <th width="12%"><strong><?= $departmentlabel ?></strong></th>
                                                            <th width="8%"><strong>Budget (Ksh)</strong></th>
                                                            <?php
                                                            if ($stage > 2) {
                                                            ?>
                                                                <th width="8%"><strong>Start Date</strong></th>
                                                                <th width="8%"><strong>End Date</strong></th>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <th width="16%"><strong>Duration (Days)</strong></th>
                                                            <?php
                                                            }
                                                            if ($stage == 2) {
                                                            ?>
                                                                <th width="10%"><strong>Substage,Status,Progress(%)</strong></th>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <th width="10%"><strong>Substage,Status</strong></th>
                                                            <?php
                                                            }
                                                            ?>
                                                            <th width="9%"><strong> <?= $level2label ?></strong></th>
                                                            <th width="7%"><strong>Issues</strong></th>
                                                            <th width="9%"><strong>Implementer</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- =========================================== -->
                                                        <?php
                                                        if (count($ind_projects) > 0) {
                                                            $sn = 0;
                                                            foreach ($ind_projects as $projid) {
                                                                $sn++;
                                                                $query_rsprojects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
                                                                $query_rsprojects->execute(array(":projid" => $projid));
                                                                $detail = $query_rsprojects->fetch();
                                                                $allprojects = $query_rsprojects->rowCount();

                                                                $projname =  $detail['projname'];
                                                                $progid =  $detail['progid'];
                                                                $projcode =  $detail['projcode'];
                                                                $projcost =  $detail['projcost'];
                                                                $projstage =  $detail['projstage'];
                                                                $substage_id =  $detail['proj_substage'];
                                                                $projstatus =  $detail['projstatus'];
                                                                $location = explode(",", $detail['projlga']);
                                                                $fscyear = $detail['projfscyear'];
                                                                $row_progid = $detail['progid'];
                                                                $duration = $detail['projduration'];
                                                                $projcategory =  $detail['projcategory'];

                                                                $progress = number_format(calculate_project_progress($projid, $projcategory), 2);
                                                                $projectid = base64_encode("projid54321{$projid}");

                                                                $project_start_date = '';
                                                                $project_end_date = '';
                                                                if ($stage > 2) {
                                                                    $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid LIMIT 1");
                                                                    $query_rsTask_Start_Dates->execute(array(':projid' => $projid));
                                                                    $rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                                    $total_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                                    if ($total_rsTask_Start_Dates > 0) {
                                                                        $project_start_date =  date_format(date_create($rows_rsTask_Start_Dates['start_date']), "d M Y");
                                                                        $project_end_date =  date_format(date_create($rows_rsTask_Start_Dates['end_date']), "d M Y");
                                                                    }
                                                                }

                                                                $query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid");
                                                                $query_rsProjissues->execute(array(":projid" => $projid));
                                                                $totalRows_rsProjissues = $query_rsProjissues->rowCount();

                                                                $projcontractor = "In House";
                                                                if ($projcategory == 2) {
                                                                    $projcontractor = "Contractor";
                                                                    $query_contractor = $db->prepare("SELECT projstartdate, projenddate, projcategory, contractor_name, contrid FROM tbl_projects p LEFT JOIN tbl_contractor c ON p.projcontractor = c.contrid WHERE projid=:projid");
                                                                    $query_contractor->execute(array(":projid" => $projid));
                                                                    $row_contractor = $query_contractor->fetch();
                                                                    $totalRows_contractor = $query_contractor->rowCount();

                                                                    if ($totalRows_contractor > 0) {
                                                                        $contractor = $row_contractor['contractor_name'];
                                                                        $projcontractor_id = $row_contractor['contrid'];
                                                                        $projcontractor_ids = base64_encode("projid54321{$projcontractor_id}");
                                                                        $projcontractor =  '<a href="view-project-contractor-info?contrid=' . $projcontractor_ids . '" style="color:#4CAF50">' . $contractor . '</a>';
                                                                    }
                                                                }

                                                                $project_progress = get_project_progress($progress);
                                                                $status = get_project_status($projstatus);
                                                                $locations = get_wards($location);
                                                                $sector = get_sector($progid);

                                                                $project_stage =  get_project_stage($projstage);
                                                        ?>
                                                                <tr id="rows">
                                                                    <td width="4%"><?php echo $sn; ?></td>
                                                                    <td width="25%" style="padding-right:0px; padding-left:0px; padding-top:0px">
                                                                        <div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
                                                                            <b>Code:</b> <?= $projcode ?><br />
                                                                            <b>Name:</b> <a href="project-dashboard?proj=<?= $projectid ?>" style="color:#FFF; font-weight:bold"><?= $projname ?></a>
                                                                        </div>
                                                                    </td>
                                                                    <td width="12%"><?= $sector ?></td>
                                                                    <td width="8%"><?= number_format($projcost, 2) ?></td>
                                                                    <?php
                                                                    if ($stage > 2) {
                                                                    ?>
                                                                        <td width="8%"><?= $project_start_date; ?></td>
                                                                        <td width="8%"><?= $project_end_date; ?></td>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <td width="8%"><?= $duration; ?></td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <td width="10%" style="padding-right:0px; padding-left:0px">
                                                                        <br />
                                                                        <?= "Sub-Stage: " . $project_stage . " <br/>" . $status  ?>
                                                                        <br />
                                                                        <?php
                                                                        if ($stage == 2) {
                                                                        ?>
                                                                            <strong>
                                                                                <?= $project_progress ?>
                                                                            </strong>
                                                                            <br />
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <?= $locations; ?>
                                                                    </td>
                                                                    <td width="7%" align="center">
                                                                        <a href="#" onclick="javascript:GetProjIssues(<?= $projid ?>)" style="color:#FF5722">
                                                                            <i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Messages"></i>
                                                                            <font size="5px"><?= $totalRows_rsProjissues ?></font>
                                                                        </a>
                                                                    </td>
                                                                    <td width="9%"><?php echo $projcontractor; ?></td>
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
                                </div>
                                <!-- end body -->
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
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $(".account").click(function() {
            var X = $(this).attr('id');

            if (X == 1) {
                $(".submenus").hide();
                $(this).attr('id', '0');
            } else {

                $(".submenus").show();
                $(this).attr('id', '1');
            }

        });

        //Mouseup textarea false
        $(".submenus").mouseup(function() {
            return false
        });
        $(".account").mouseup(function() {
            return false
        });


        //Textarea without editing.
        $(document).mouseup(function() {
            $(".submenus").hide();
            $(".account").attr('id', '');
        });

    });

    function GetScorecard(projid) {
        var prog = $("#scardprog").val();
        $.ajax({
            type: 'post',
            url: 'getscorecard',
            data: {
                prjid: projid,
                scprog: prog
            },
            success: function(data) {
                $('#formcontent').html(data);
                $("#myModal").modal({
                    backdrop: "static"
                });
            }
        });
    }

    function GetProjDetails(projid) {
        $.ajax({
            type: 'post',
            url: 'general-settings/selected-items/fetch-selected-project-details',
            data: {
                itemId: projid
            },
            success: function(data) {
                $('#detailscontent').html(data);
                $("#projDetails").modal({
                    backdrop: "static"
                });
            }
        });
    }

    function GetProjIssues(projid) {
        $.ajax({
            type: 'post',
            url: 'getprojissues',
            data: {
                prjid: projid
            },
            success: function(data) {
                $('#issuescontent').html(data);
                $("#projIssues").modal({
                    backdrop: "static"
                });
            }
        });
    }
</script>