<?php
require('includes/head.php');
if ($permission) {
    try {
        $sql_ind_projects = $db->prepare("SELECT * FROM `tbl_projects` p left join `tbl_programs` g on g.progid=p.progid WHERE program_type=0 and p.deleted='0'");
        $sql_ind_projects->execute();
        $totalRows_ind_projects = $sql_ind_projects->rowCount();

        $sql_ind_programs = $db->prepare("SELECT * FROM `tbl_programs` WHERE program_type=0 ORDER BY `syear` ASC");
        $sql_ind_programs->execute();
        $totalRows_ind_programs = $sql_ind_programs->rowCount();

        function get_source_categories()
        {
            global $db;
            $query_rsFunding_type =  $db->prepare("SELECT * FROM tbl_financier_type WHERE status=1");
            $query_rsFunding_type->execute();
            $totalRows_rsFunding_type = $query_rsFunding_type->rowCount();
            $input = '';
            if ($totalRows_rsFunding_type > 0) {
                while ($row_rsFunding_type = $query_rsFunding_type->fetch()) {
                    $input .= '<option value="' . $row_rsFunding_type['id'] . '"> ' . $row_rsFunding_type['type'] . '</option>';
                }
            }
            return $input;
        }

        function get_partner_roles()
        {
            global $db;
            $query_rsParners =  $db->prepare("SELECT * FROM tbl_partner_roles");
            $query_rsParners->execute();
            $totalRows_rsParners = $query_rsParners->rowCount();
            $input = '';
            if ($totalRows_rsParners > 0) {
                while ($row_rsParners = $query_rsParners->fetch()) {
                    $input .= '<option value="' . $row_rsParners['id'] . '"> ' . $row_rsParners['role'] . '</option>';
                }
            }
            return $input;
        }

        function get_partners()
        {
            global $db;
            $query_rsParners =  $db->prepare("SELECT * FROM tbl_partners WHERE active=1");
            $query_rsParners->execute();
            $totalRows_rsParners = $query_rsParners->rowCount();
            $input = '';
            if ($totalRows_rsParners > 0) {
                while ($row_rsParners = $query_rsParners->fetch()) {
                    $input .= '<option value="' . $row_rsParners['id'] . '"> ' . $row_rsParners['partner'] . '</option>';
                }
            }
            return $input;
        }

        function mne_plan($projid, $projevaluation, $projimpact)
        {
            global $db;
            $outcome = false;
            if ($projevaluation == 1) {
                $sql = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE projid = :projid ORDER BY `id` ASC");
                $sql->execute(array(":projid" => $projid));
                $rows_count = $sql->rowCount();
                $outcome = $rows_count > 0 ? true : false;
            }

            $impact = false;
            if ($projimpact == 1) {
                $sql = $db->prepare("SELECT * FROM `tbl_project_expected_outcome_details` WHERE projid = :projid ORDER BY `id` ASC");
                $sql->execute(array(":projid" => $projid));
                $row_count = $sql->rowCount();
                $impact = $row_count > 0 ? true : false;
            }

            $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid ");
            $query_rsOutput->execute(array(":projid" => $projid));
            $totalRows_rsOutput = $query_rsOutput->rowCount();
            $output = $totalRows_rsOutput > 0 ? true : false;

            return $output || $outcome || $impact ? true : false;
        }

        function check_approve_project($projid, $projevaluation, $projimpact)
        {
            global $db, $projid;
            $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid ");
            $query_rsOutput->execute(array(":projid" => $projid));
            $totalRows_rsOutput = $query_rsOutput->rowCount();
            $output = $totalRows_rsOutput > 0 ? true : false;
            $outcome = true;
            if ($projevaluation == 1) {
                $sql = $db->prepare("SELECT * FROM `tbl_project_expected_outcome_details` WHERE projid = :projid");
                $sql->execute(array(":projid" => $projid));
                $rows_count = $sql->rowCount();
                $outcome = $rows_count > 0 ? true : false;
            }

            $impact = true;
            if ($projimpact == 1) {
                $sql = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE projid = :projid");
                $sql->execute(array(":projid" => $projid));
                $row_count = $sql->rowCount();
                $impact = $row_count > 0 ? true : false;
            }
            return $output && $outcome && $impact ? true : false;
        }


        $source_categories = get_source_categories();
        $partner_roles  = get_partner_roles();
        $partners  = get_partners();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon . " " . $pageTitle ?>
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
                        <div class="card-header">
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Independent Programs &nbsp;<span class="badge bg-orange" id="total-programs">0</span></a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Independent Projects &nbsp;<span class="badge bg-blue" id="total-projects">0</span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <div class="card-header">
                                        <div class="pull-right">
                                            <a href="add-program.php?program_type=0" class="btn btn-success">New Program </a>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="indepedentPrograms" style="width:100%" id="indepedentPrograms">
                                                <thead style="width:100%">
                                                    <tr class="bg-orange" style="width:100%">
                                                        <th width="3%">#</th>
                                                        <th width="40%">Program</th>
                                                        <th width="13%">Budget (ksh)</th>
                                                        <th width="15%">Budget Bal (ksh)</th>
                                                        <th width="4%">Project(s)</th>
                                                        <th width="10%">Start Year </th>
                                                        <th width="8%">Duration </th>
                                                        <th width="7%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = $db->prepare("SELECT * FROM `tbl_programs` WHERE program_type=0 ORDER BY `syear` ASC");
                                                    $sql->execute();
                                                    $rows_count = $sql->rowCount();
                                                    $output = array('data' => array());
                                                    if ($rows_count > 0) {
                                                        $sn = 0;
                                                        while ($row_rsProgram = $sql->fetch()) {
                                                            $progid = $row_rsProgram['progid'];
                                                            $program_type = $row_rsProgram['program_type'];
                                                            $strategic_plan = $row_rsProgram['strategic_plan'];
                                                            $created_by = $row_rsProgram['createdby'];
                                                            $progid_hashed = base64_encode("progid54321{$progid}");
                                                            $project_department = $row_rsProgram['projsector'];
                                                            $project_section = $row_rsProgram['projdept'];
                                                            $project_directorate = $row_rsProgram['directorate'];
                                                            $progname =  $row_rsProgram['progname'];
                                                            $projduration = $row_rsProgram['years'] . " Years";
                                                            $projsyear = $row_rsProgram['syear'];

                                                            $yr = date("Y");
                                                            $mnth = date("m");
                                                            $startmnth = 07;
                                                            $endmnth = 06;

                                                            if ($mnth >= 7 && $mnth <= 12) {
                                                                $fnyear = $yr;
                                                            } elseif ($mnth >= 1 && $mnth <= 6) {
                                                                $fnyear = $yr - 1;
                                                            }

                                                            //fetch budget
                                                            $query_projs =  $db->prepare("SELECT projid FROM tbl_projects WHERE progid='$progid'");
                                                            $query_projs->execute();
                                                            $totalRows_projs = $query_projs->rowCount();

                                                            // get program quarterly targets
                                                            $query_pbbtargets =  $db->prepare("SELECT * FROM tbl_independent_programs_quarterly_targets WHERE progid = :progid");
                                                            $query_pbbtargets->execute(array(":progid" => $progid));
                                                            $norows_pbbtargets = $query_pbbtargets->rowCount();

                                                            $button = '';
                                                            if ($norows_pbbtargets == 0 && $totalRows_projs == 0) {
                                                                if (in_array("update", $page_actions)) {
                                                                    $button .= '<li><a type="button" data-toggle="modal" id="editprogram"  href="edit-program?progid=' . $progid_hashed . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>';
                                                                }

                                                                if (in_array("delete", $page_actions)) {
                                                                    $button .= '<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="destroy_program(' . $progid . ')"> <i class="glyphicon glyphicon-trash"></i> Delete</a></li>';
                                                                }
                                                            }

                                                            if ($norows_pbbtargets == 0) {
                                                                $details = "{
                                                                    program_name:'$progname',
                                                                    progid:'$progid',
                                                                    edit:'0'
                                                                }";
                                                                if (in_array("create_quarterly_targets", $page_actions)) {
                                                                    $button .= '
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" id="quarterlyTargetsModalBtn" data-target="#quarterlyTargetsModal" onclick="add_independent_quarterly_targets(' . $details . ')">
                                                                            <i class="fa fa-plus-square-o"></i> Add Quarterly Targets
                                                                        </a>
                                                                    </li>';
                                                                }
                                                            } else {
                                                                $details = "{
                                                                    program_name:'$progname',
                                                                    progid:'$progid',
                                                                    edit:'1'
                                                                }";
                                                                $query_projects_count = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$progid' AND projstage > 0");
                                                                $query_projects_count->execute();
                                                                $count_projects_count = $query_projects_count->rowCount();
                                                                if (in_array("update_quarterly_targets", $page_actions)  && $count_projects_count == 0) {
                                                                    $button .= '
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" id="editquarterlyTargetsModalBtn" data-target="#quarterlyTargetsModal" onclick="add_independent_quarterly_targets(' . $details . ')">
                                                                            <i class="glyphicon glyphicon-edit"></i> Edit Quarterly Targets
                                                                        </a>
                                                                    </li>';
                                                                }
                                                            }

                                                            if (in_array("create", $page_actions)) {
                                                                $button .= '<li><a type="button" id="addproject"  href="add-project.php?progid=' . $progid_hashed . '" > <i class="fa fa-plus-square"></i> Add Project</a></li>';
                                                            }

                                                            //get financial years
                                                            $query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE yr='$projsyear'");
                                                            $query_rsYear->execute();
                                                            $row_rsYear = $query_rsYear->fetch();
                                                            $totalRows_rsYear = $query_rsYear->rowCount();
                                                            $projsyear = $row_rsYear['year'];

                                                            //fetch budget
                                                            $query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_progdetails WHERE progid='$progid'");
                                                            $query_rsBudget->execute();
                                                            $row_rsBudget = $query_rsBudget->fetch();
                                                            $totalRows_rsBudget = $query_rsBudget->rowCount();
                                                            $progbudget = number_format($row_rsBudget['budget'], 2);

                                                            //get project department
                                                            $progdepart = $row_rsProgram['projdept'];
                                                            $query_rsDepart = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE stid='$progdepart' ");
                                                            $query_rsDepart->execute();
                                                            $row_rsDepart = $query_rsDepart->fetch();
                                                            $dept = $row_rsDepart['sector'];

                                                            //get total projects
                                                            $query_projs = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$progid'");
                                                            $query_projs->execute();
                                                            $count_projs = $query_projs->rowCount();

                                                            if ($count_projs > 0) {
                                                                $projectscount = '<a href="view-indipendent-projects.php?prg=' . $progid_hashed . '"><span class="badge bg-purple">' . $count_projs . '</span></a>';
                                                            } else {
                                                                $projectscount = '<a href="#"><span class="badge bg-purple">' . $count_projs . '</span></a>';
                                                            }


                                                            //get total projects
                                                            $query_projsbudget = $db->prepare("SELECT SUM(projcost) as budget FROM tbl_projects WHERE progid = '$progid'");
                                                            $query_projsbudget->execute();
                                                            $row_projsbudget = $query_projsbudget->fetch();
                                                            $count_projsbudget = $query_projsbudget->rowCount();

                                                            $projsbudget = ($count_projsbudget > 0) ?  $row_projsbudget['budget'] : 0;
                                                            $progbudgetbal = number_format(($row_rsBudget['budget'] - $projsbudget), 2);
                                                            $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                            if ($filter_department) {
                                                                $sn++;
                                                    ?>
                                                                <tr>
                                                                    <td><?= $sn ?></td>
                                                                    <td><?= $progname ?></td>
                                                                    <td><?= $progbudget ?></td>
                                                                    <td><?= $progbudgetbal ?></td>
                                                                    <td><?= $projectscount ?></td>
                                                                    <td><?= $projsyear ?></td>
                                                                    <td><?= $projduration ?></td>
                                                                    <td>
                                                                        <!-- Single button -->
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li><a type="button" data-toggle="modal" data-target="#progmoreInfoModal" id="progmoreInfoModalBtn" onclick="program_info(<?= $progid ?>)">
                                                                                        <i class="glyphicon glyphicon-file"></i> More Info</a>
                                                                                </li>
                                                                                <?= $button ?>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                    <?php
                                                            }
                                                        } // /while

                                                    } // if num_rows
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="indprojects" style="width:100%">
                                                <thead>
                                                    <tr class="bg-blue">
                                                        <th width="3%">#</th>
                                                        <th width="40%">Project</th>
                                                        <th width="13%">Budget (ksh)</th>
                                                        <th width="14%">Start Year </th>
                                                        <th width="14%">Duration (day)</th>
                                                        <th width="10%">Status </th>
                                                        <th width="8%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = $db->prepare("SELECT * FROM `tbl_programs` g left join `tbl_projects` p on p.progid=g.progid left join tbl_fiscal_year y on y.id=p.projfscyear left join tbl_status s on s.statusid=p.projstatus WHERE g.program_type=0 AND p.deleted='0' ORDER BY `projfscyear` DESC");
                                                    $sql->execute();
                                                    $rows_count = $sql->rowCount();
                                                    $output = array('data' => array());
                                                    if ($rows_count > 0) {
                                                        $sn = 0;
                                                        while ($row_project = $sql->fetch()) {
                                                            $projid = $row_project['projid'];
                                                            $projname = $row_project['projname'];
                                                            $projcost = number_format($row_project['projcost'], 2);
                                                            $projstatusid = $row_project['projstatus'];
                                                            $projstatus = $row_project['statusname'];
                                                            $projfscyear = $row_project['year'];
                                                            $strategic_plan = $row_project['strategic_plan'];
                                                            $projduration = $row_project['projduration'];
                                                            $program_type = $row_project['program_type'];
                                                            $approved = $row_project['projplanstatus'];
                                                            $username = $row_project['user_name'];
                                                            $projstage = $row_project['projstage'];
                                                            $projevaluation = $row_project['projevaluation'];
                                                            $projimpact = $row_project['projimpact'];
                                                            $projid_hashed = base64_encode("projid54321{$projid}");


                                                            $project_department = $row_project['projsector'];
                                                            $project_section = $row_project['projdept'];
                                                            $project_directorate = $row_project['directorate'];

                                                            //fetch budget
                                                            $query_projs =  $db->prepare("SELECT projid FROM tbl_projects WHERE progid='$projfscyear'");
                                                            $query_projs->execute();
                                                            $totalRows_projs = $query_projs->rowCount();

                                                            $projstatus = "<label class='label label-danger'>Pending Approval</div>";
                                                            $action = "";
                                                            $button = ' ';
                                                            if ($approved == 1) {
                                                                $projstatus = "<label class='label label-success'>Approved</div>";
                                                                $action = '';
                                                                if (in_array("unapprove", $page_actions) && $projstage == 0) {
                                                                    $button .= '<li><a type="button" onclick="unapprove_project(' . $projid . ')"> <i class="glyphicon glyphicon-edit"></i> Unapprove</a></li>';
                                                                }
                                                            } else {
                                                                if (mne_plan($projid, $projevaluation, $projimpact)) {
                                                                    if (in_array("create", $page_actions)) {
                                                                        $button .= '<li><a type="button" href="add-project-mne-plan.php?projid=' . $projid_hashed . '"> <i class="glyphicon glyphicon-plus"></i>Edit M&E Plan</a></li>';
                                                                    }

                                                                    if (check_approve_project($projid, $projevaluation, $projimpact)) {
                                                                        if (in_array("approve_project", $page_actions)) {
                                                                            $button .= '<li><a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#approveItemModal" onclick="approve_project(' . $projid . ')"> <i class="fa fa-check-square-o"></i> Approve Project </a></li>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    if (in_array("create", $page_actions)) {
                                                                        $button .= '<li><a type="button" href="add-project-mne-plan.php?projid=' . $projid_hashed . '"> <i class="glyphicon glyphicon-plus"></i>Add M&E Plan</a></li>';
                                                                    }

                                                                    $projstatus = "<label class='label label-danger'>Pending M&E Plan</div>";

                                                                    if (in_array("update", $page_actions)) {
                                                                        $button .= '<li><a type="button" href="add-project.php?projid=' . $projid_hashed . '"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>';
                                                                    }

                                                                    if (in_array("delete", $page_actions)) {
                                                                        $button .= '<li><a type="button"  id="removeItemModalBtn" onclick="removeItem(' . $projid . ')"> <i class="glyphicon glyphicon-trash"></i> Delete</a></li>';
                                                                    }
                                                                }
                                                            }
                                                            $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                            if ($filter_department) {
                                                                $sn++;
                                                    ?>
                                                                <tr>
                                                                    <td><?= $sn ?> </td>
                                                                    <td><?= $projname ?> </td>
                                                                    <td><?= $projcost ?> </td>
                                                                    <td><?= $projfscyear  ?></td>
                                                                    <td><?= $projduration ?> </td>
                                                                    <td><?= $projstatus ?> </td>
                                                                    <td>
                                                                        <!-- Single button -->
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li><a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="project_info(<?= $projid ?>)"> <i class="glyphicon glyphicon-file"></i> More Info</a></li>
                                                                                <?= $button ?>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                    <?php
                                                            }
                                                        } // /while
                                                    } // if num_rows
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- End PAge Content -->
                            <!-- ============================================================== -->
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->

    <!-- Start Modal Add Quarterly Targets -->
    <div class="modal fade" id="quarterlyTargetsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Program Quarterly Targets</h4>
                </div>
                <div class="modal-body">
                    <div class="div-result">
                        <form class="form-horizontal" id="quarterlyTargetsForm" action="" method="POST">
                            <br />
                            <div id="quarterlyTargetsBody">

                            </div>
                            <div class="modal-footer approveItemFooter">
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="indepedentProgramsquarterlytargets" id="indepedentProgramsquarterlytargets" value="1">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div> <!-- /modal-footer -->
                        </form> <!-- /.form -->
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>


    <!-- Start Modal Add Quarterly Targets -->
    <div class="modal fade" id="editquarterlyTargetsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Program Quarterly Targets</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="div-result">
                        <form class="form-horizontal" id="editquarterlyTargetsForm" action="" method="POST">
                            <br />
                            <div class="col-md-12" id="editquarterlyTargetsBody">
                            </div>
                            <div class="modal-footer approveItemFooter">
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="editindependentprogramquarterlytargets" id="editindependentprogramquarterlytargets" value="1">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Update" />
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div> <!-- /modal-footer -->
                        </form> <!-- /.form -->
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>

    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="progmoreInfoModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info" style="font-size:24px"></i> Program More Information</h4>
                </div>
                <div class="modal-body" id="progmoreinfo">
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Item more -->

    <!-- Start projects Item more Info -->
    <div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> Project More Information</h4>
                </div>
                <div class="modal-body" id="moreinfo">
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End  Item more Info -->



    <!-- Start Modal Item approve -->
    <div class="modal fade" id="approveItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Approve Project</h4>
                </div>
                <div class="modal-body" style="max-height:auto; overflow:auto;">
                    <div class="div-result">
                        <form class="form-horizontal" id="approveItemForm" action="" method="POST">
                            <br />
                            <div class="col-md-12" id="aproveBody"></div>
                            <div class="modal-footer approveItemFooter">
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="approveitem" id="approveitem" value="1">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Approve" />
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div> <!-- /modal-footer -->
                        </form> <!-- /.form -->
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>

<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>

<script>
    const details = {
        partner_roles: '<?= $partner_roles ?>',
        source_categories: '<?= $source_categories ?>',
        partners: '<?= $partners ?>',
    }
</script>

<script src="assets/js/projects/view-project.js"></script>
<script src="assets/js/projects/approve.js"></script>
<script src="assets/js/programs/view-programs.js"></script>