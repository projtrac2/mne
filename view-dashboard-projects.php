<?php
require('includes/head.php');
if ($permission) {
    try {
        $accesslevel = "";
        if (($user_designation < 5)) {
            $accesslevel = "";
        } elseif ($user_designation == 5) {
            $accesslevel = " AND g.projsector=$user_department";
        } elseif ($user_designation == 6) {
            $accesslevel = " AND g.projsector=$user_department AND g.projdept=$user_section";
        } elseif ($user_designation > 6) {
            $accesslevel = " AND g.projsector=$user_department AND g.projdept=$user_section AND g.directorate=$user_directorate";
        }

        $status_array = array(
            'all' => array("status" => ''),
            'complete' => array("status" => 5),
            'on-track' => array("status" => 4),
            'pending' => array("status" => 3),
            'behind-schedule' => array("status" => 11),
            'awaiting-procurement' => array("status" => 1),
            'on-hold' => array("status" => 6),
            'cancelled' => array("status" => 2),
        );


        function widgets_filter($from = null, $to = null, $level1 = null, $level2 = null, $level3 = null, $prjstatus, $accesslevel = null, $program_type)
        {
            $widget_array = '';
            if ($from != null) {
                if ($to != null) {
                    if ($level1 != null) {
                        if ($level2 != null) {
                            if ($level3 != null) {
                                // select for only from, to, level 1, 2 and 3
                                $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                                $widget_array = widgets($sql, $level1, $level2, $level3, $prjstatus, $accesslevel, $program_type);
                            } else {
                                // select for only from, to, level 1 and 2
                                $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                                $widget_array = widgets($sql, $level1, $level2, $level3 = null, $prjstatus, $accesslevel, $program_type);
                            }
                        } else {
                            // select for only from, to and level 1
                            $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                            $widget_array = widgets($sql, $level1, $level2 = null, $level3 = null, $prjstatus, $accesslevel, $program_type);
                        }
                    } else {
                        // select for only from, to and to
                        $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                        $widget_array = widgets($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus, $accesslevel, $program_type);
                    }
                } else {
                    if ($level1 != null) {
                        if ($level2 != null) {
                            if ($level3 != null) {
                                // select for only from, level 1, 2 and 3
                                $sql = "p.projfscyear >=" . $from;
                                $widget_array = widgets($sql, $level1, $level2, $level3, $prjstatus, $accesslevel, $program_type);
                            } else {
                                // select for only from, level 1 and 2
                                $sql = "p.projfscyear >=" . $from;
                                $widget_array = widgets($sql, $level1, $level2, $level3 = null, $prjstatus, $accesslevel, $program_type);
                            }
                        } else {
                            // select for only from and level 1
                            $sql = "p.projfscyear >=" . $from;
                            $widget_array = widgets($sql, $level1, $level2 = null, $level3 = null, $prjstatus, $accesslevel, $program_type);
                        }
                    } else {
                        // select for only from
                        $sql = "p.projfscyear >=" . $from;
                        $widget_array = widgets($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus, $accesslevel, $program_type);
                    }
                }
            } else {
                if ($level1 != null) {
                    if ($level2 != null) {
                        if ($level3 != null){
                            // select for only level 1, 2 and 3
                            $widget_array = widgets($sql = null, $level1, $level2, $level3, $prjstatus, $accesslevel,$program_type);
                        } else {
                            // select for only level 1 and 2
                            $widget_array =  widgets($sql = null, $level1, $level2, $level3 = null, $prjstatus, $accesslevel,$program_type);
                        }
                    } else {
                        // select for only level 1
                        $widget_array = widgets($sql = null, $level1, $level2 = null, $level3 = null, $prjstatus, $accesslevel,$program_type);
                    }
                }
            }
            return $widget_array;
        }

        function widgets($query = NULL, $level1 = NULL, $level2 = NULL, $level3 = NULL, $projstatus = null, $accesslevel = null, $program_type)
        {
            global $status_array, $db;
            $projids_array  = array();
            $project_status = $status_array[$projstatus];
            $status = $project_status['status'];
            $stmt = '';
            if ($status != '') {
                $stmt = "p.projstatus =" . $status;
            }
            $where = $stmt != "" ? $accesslevel . " AND " . $stmt : $accesslevel;
            $where  = $query != null ? $where . " AND " . $query : $where;

            $sql = "SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage > 0 AND p.deleted = '0'  AND g.program_type=$program_type AND projstage >= 1" . $where;
            $query_rsprojects = $db->prepare($sql);
            $query_rsprojects->execute();
            $row_projects = $query_rsprojects->fetch();
            $allprojects = $query_rsprojects->rowCount();
            if ($allprojects > 0) {
                $projids_array = [];
                do {
                    $projid = $row_projects['projid'];
                    $projcommunity = explode(",", $row_projects['projcommunity']);
                    $projlga = explode(",", $row_projects['projlga']);

                    if ($level1 != null) {
                        if ($level2 != null) {
                                if (in_array($level2, $projlga)) {
                                    $projids_array[] = $projid;
                                }

                        } else {
                            if (in_array($level1, $projcommunity)) {
                                $projids_array[] = $projid;
                            }
                        }
                    } else {
                        $projids_array[] = $projid;
                    }
                } while ($row_projects = $query_rsprojects->fetch());
            }

            return $projids_array;
        }



        if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
            $prjstatus = $_GET['prjstatus'];
            $prjfyfrom = $_GET['projfyfrom'];
            $prjfyto = $_GET['projfyto'];
            $prjsc = $_GET['projscounty'];
            $prjward = $_GET['projward'];
            $prjloc = $_GET['projlocation'];



            if (empty($prjfyfrom) && empty($prjsc)) {
                $widget_data = widgets($query = NULL, $prjsc = NULL, $prjward = NULL, $prjloc = NULL, $prjstatus, $accesslevel, 1);
                $projects = (count($widget_data) > 0) ?  array_unique($widget_data) : array();

                $widget_data_indipendent = widgets($query = NULL, $prjsc = NULL, $prjward = NULL, $prjloc = NULL, $prjstatus, $accesslevel, 0);
                $ind_projects = (count($widget_data_indipendent) > 0) ?  array_unique($widget_data_indipendent) : array();
            } else {
                $widget_data = widgets_filter($prjfyfrom, $prjfyto, $prjsc, $prjward, $prjloc, $prjstatus, $accesslevel, 1);
                $projects = (count($widget_data) > 0) ?  array_unique($widget_data) : array();

                $widget_data_indipendent = widgets_filter($prjfyfrom, $prjfyto, $prjsc, $prjward, $prjloc, $prjstatus, $accesslevel, 0);
                $ind_projects = (count($widget_data_indipendent) > 0) ?  array_unique($widget_data_indipendent) : array();
            }
        } else {
            $prjstatus = 'all';
            if (isset($_GET['prjstatus'])) {
                $prjstatus = $_GET['prjstatus'];
            }
            $widget_data = widgets($query = NULL, $prjsc = NULL, $prjward = NULL, $prjloc = NULL, $prjstatus, $accesslevel, 1);
            $projects = (count($widget_data) > 0) ?  array_unique($widget_data) : array();

            $widget_data_indipendent = widgets($query = NULL, $prjsc = NULL, $prjward = NULL, $prjloc = NULL, $prjstatus, $accesslevel, 0);
            $ind_projects = (count($widget_data_indipendent) > 0) ?  array_unique($widget_data_indipendent) : array();
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
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
                                                        <th width="25%"><strong>Project</strong></th>
                                                        <th width="12%"><strong><?= $departmentlabel ?></strong></th>
                                                        <th width="8%"><strong>Budget (Ksh)</strong></th>
                                                        <th width="8%"><strong>Start Date</strong></th>
                                                        <th width="8%"><strong>End Date</strong></th>
                                                        <th width="10%"><strong>Status & Progress(%)</strong></th>
                                                        <th width="9%"><strong>Location</strong></th>
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
                                                            $projstatus =  $detail['projstatus'];
                                                            $location = explode(",", $detail['projlga']);
                                                            $fscyear = $detail['projfscyear'];
                                                            $row_progid = $detail['progid'];
                                                            $projcategory =  $detail['projcategory'];
                                                            $percent2 =  $detail['progress'];
															$projectid = base64_encode("projid54321{$projid}");
															$project_start_date = date_format(date_create($detail['projstartdate']), "d M Y");
															$project_end_date = date_format(date_create($detail['projenddate']), "d M Y");

															if ($projcategory == 2 && $projstage > 4) {
																$query_tender_details = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
																$query_tender_details->execute(array(":projid" => $projid));
																$rows_tender_details = $query_tender_details->fetch();
                                                                $total_tender_details = $query_tender_details->rowCount();
                                                                if ($total_tender_details > 0) {
																	$project_start_date =  date_format(date_create($rows_tender_details['startdate']), "d M Y");
																	$project_end_date =  date_format(date_create($rows_tender_details['enddate']), "d M Y");
																}
															} elseif ($projcategory == 1 && $projstage > 8) {
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

															if($projcategory == 2){
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
                                                                    ' . number_format($percent2, 2) . '%
                                                                </div>
                                                            </div>';
                                                            if ($percent2 == 100) {
                                                                $project_progress = '
                                                                <div class="progress" style="height:20px; font-size:10px; color:black">
                                                                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
                                                                    ' . number_format($percent2, 2) . '%
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
                                                                <td width="8%"><?= number_format($projcost, 2)?></td>
                                                                <td width="8%"><?= $project_start_date; ?></td>
                                                                <td width="8%"><?= $project_end_date; ?></td>
                                                                <td width="10%" style="padding-right:0px; padding-left:0px">
                                                                    <?= $status  ?>
                                                                    <br />
                                                                    <strong>
                                                                        <?= $project_progress ?>
                                                                    </strong>
                                                                    <br />
                                                                </td>
                                                                <td width="9%">
                                                                    <?= implode(", ", $locations); ?>
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
                                                        <th width="8%"><strong>Start Date</strong></th>
                                                        <th width="8%"><strong>End Date</strong></th>
                                                        <th width="10%"><strong>Status & Progress(%)</strong></th>
                                                        <th width="9%"><strong>Location</strong></th>
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
                                                            $projstatus =  $detail['projstatus'];
                                                            $location = explode(",", $detail['projlga']);
                                                            $fscyear = $detail['projfscyear'];
                                                            $row_progid = $detail['progid'];
                                                            $projcategory =  $detail['projcategory'];
                                                            $percent2 = $detail['progress'];
															$projectid = base64_encode("projid54321{$projid}");
															$project_start_date = date_format(date_create($detail['projstartdate']), "d M Y");
															$project_end_date = date_format(date_create($detail['projenddate']), "d M Y");

															if ($projcategory == 2 && $projstage > 4) {
																$query_tender_details = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
																$query_tender_details->execute(array(":projid" => $projid));
																$rows_tender_details = $query_tender_details->fetch();
                                                                $total_tender_details = $query_tender_details->rowCount();
                                                                if ($total_tender_details > 0) {
																	$project_start_date =  date_format(date_create($rows_tender_details['startdate']), "d M Y");
																	$project_end_date =  date_format(date_create($rows_tender_details['enddate']), "d M Y");
																}
															} elseif ($projcategory == 1 && $projstage > 8) {
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

															if($projcategory == 2){
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
                                                                    ' . number_format($percent2, 2) . '%
                                                                </div>
                                                            </div>';
                                                            if ($percent2 == 100) {
                                                                $project_progress = '
                                                                <div class="progress" style="height:20px; font-size:10px; color:black">
                                                                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
                                                                    ' . number_format($percent2, 2) . '%
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
                                                                <td width="8%"><?= $project_start_date; ?></td>
                                                                <td width="8%"><?= $project_end_date; ?></td>
                                                                <td width="10%" style="padding-right:0px; padding-left:0px">
                                                                    <?= $status  ?>
                                                                    <br />
                                                                    <strong>
                                                                        <?= $project_progress ?>
                                                                    </strong>
                                                                    <br />
                                                                </td>
                                                                <td width="9%">
                                                                    <?= implode(", ", $locations); ?>
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