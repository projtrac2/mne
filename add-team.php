<?php
try {

    require('includes/head.php');
    if ($permission && (isset($_GET['projid']) && !empty($_GET["projid"]))) {
        $decode_projid =   base64_decode($_GET['projid']);
        $projid_array = explode("encodeprocprj", $decode_projid);
        $projid = $projid_array[1];

        $query_rsProjects = $db->prepare("SELECT p.*, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid  WHERE p.deleted='0' and projid=:projid AND projstage=:projstage");
        $query_rsProjects->execute(array(":projid" => $projid, ":projstage" => $workflow_stage));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        if ($totalRows_rsProjects > 0) {
            $projname = $row_rsProjects['projname'];
            $workflow_stage =  $row_rsProjects['projstage'];
            $sub_stage = $row_rsProjects['proj_substage'];
            $implementation_type = $row_rsProjects['projcategory'];
            $baseline_survey = $row_rsProjects['projevaluation'];
            $projcode = $row_rsProjects['projcode'];
            $project_directorate = $row_rsProjects['directorate'];
            $project_sub_stage = $row_rsProjects['proj_substage'];
            $approval_stage = ($sub_stage  >= 2) ? true : false;

?>
            <!-- start body  -->
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon . " " . $pageTitle ?>
                            <div class="btn-group" style="float:right">
                                <div class="btn-group" style="float:right">
                                    <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
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
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="list-group">
                                                <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
                                                <li class="list-group-item"><strong>Project Code: </strong> <?= $projcode ?> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <!-- ============================================================== -->
                                    <!-- Start Page Content -->
                                    <!-- ============================================================== -->
                                    <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                        <?= csrf_token_html(); ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example ">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width:25%">Member *</th>
                                                        <th style="width:25%"><?= $ministrylabel ?> *</th>
                                                        <th style="width:25%"><?= $departmentlabel ?></th>
                                                        <th style="width:15%">Role</th>

                                                        <th style="width:5%" data-orderable="false">
                                                            <button type="button" name="addplus" id="add_row_gen" data-toggle="modal" data-target="#outputItemModal" onclick="add_team_members(0)" class="btn btn-success btn-sm">
                                                                <span class="glyphicon glyphicon-plus">
                                                                </span>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="financier_table_body">
                                                    <?php
                                                    $query_rsMembers = $db->prepare("SELECT responsible, role FROM `tbl_projmembers` WHERE projid=:projid  AND stage=:workflow_stage and team_type=4");
                                                    $query_rsMembers->execute(array(":projid" => $projid, ":workflow_stage" => 9));
                                                    $total_rsMembers = $query_rsMembers->rowCount();
                                                    if ($total_rsMembers > 0) {
                                                        $rowno = 0;
                                                        while ($row_rsMembers = $query_rsMembers->fetch()) {
                                                            $rowno++;
                                                            $role_id = $row_rsMembers['role'];
                                                            $ptid = $row_rsMembers['responsible'];
                                                            $get_user = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id ");
                                                            $get_user->execute(array(":user_id" => $ptid));
                                                            $count_user = $get_user->rowCount();
                                                            $row_teams = $get_user->fetch();

                                                            $department_id = $row_teams ? $row_teams['ministry'] : "";
                                                            $section_id = $row_teams ? $row_teams['department'] : "";
                                                            $fname = $row_teams ? $row_teams['fullname'] : "";
                                                            $title_id = $row_teams ? $row_teams['title'] : "";

                                                            $query_rsTitle = $db->prepare("SELECT * FROM `tbl_titles` WHERE id=:title_id ");
                                                            $query_rsTitle->execute(array(":title_id" => $title_id));
                                                            $row_rsTitle = $query_rsTitle->fetch();
                                                            $title = $row_rsTitle ? $row_rsTitle['title'] : '';
                                                            $membername = $title . ". " . $fname;

                                                            $query_department = $db->prepare("SELECT * FROM `tbl_sectors` WHERE stid=:stid ");
                                                            $query_department->execute(array(":stid" => $department_id));
                                                            $row_department = $query_department->fetch();
                                                            $department = $row_department ? $row_department['sector'] : "";

                                                            $query_sector = $db->prepare("SELECT * FROM `tbl_sectors` WHERE stid=:stid ");
                                                            $query_sector->execute(array(":stid" => $section_id));
                                                            $row_sector = $query_sector->fetch();
                                                            $sector = $row_sector ? $row_sector['sector'] : "";

                                                            $query_projrole = $db->prepare("SELECT * FROM `tbl_project_team_roles` WHERE id=:role_id");
                                                            $query_projrole->execute(array(":role_id" => $role_id));
                                                            $row_projrole = $query_projrole->fetch();
                                                            $role = $row_projrole ? $row_projrole['role'] : "";
                                                            $f_name = htmlspecialchars($membername);

                                                            $details = "{
                                                                projid:$projid,
                                                                full_name:'$f_name',
                                                                user_id:$ptid,
                                                            }";
                                                    ?>
                                                            <tr id="finrow<?= $rowno ?>">
                                                                <td><?= $rowno  ?></td>
                                                                <td><?= $membername  ?></td>
                                                                <td><?= $department  ?></td>
                                                                <td><?= $sector  ?></td>
                                                                <td><?= $role  ?></td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#outputItemModal" id="assignModalBtn" onclick="add_team_members(<?= $ptid ?>, <?= $role_id ?>)">
                                                                                    <i class="fa fa-pencil"></i> Edit
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a type="button" onclick="delete_team_member(<?= $details ?>)" id="addFormModalBtn">
                                                                                    <i class="fa fa-trash"></i> Delete
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                    <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                        <div class="col-md-12 text-center">
                                            <?php
                                            function validate_team()
                                            {
                                                global $db, $projid;
                                                $query_rsTasks = $db->prepare("SELECT * FROM tbl_task t INNER JOIN tbl_project_direct_cost_plan c ON t.tkid = c.subtask_id WHERE t.projid=:projid ");
                                                $query_rsTasks->execute(array(":projid" => $projid));
                                                $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                $result = [];
                                                if ($totalRows_rsTasks > 0) {
                                                    while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                        $site_id = $row_rsTasks['site_id'];
                                                        $task_id = $row_rsTasks['subtask_id'];
                                                        $query_rsSubTask = $db->prepare("SELECT * FROM tbl_member_subtasks WHERE subtask_id=:subtask_id AND site_id=:site_id ");
                                                        $query_rsSubTask->execute(array(":subtask_id" => $task_id, ":site_id" => $site_id));
                                                        $totalRows_rsSubTask = $query_rsSubTask->rowCount();
                                                        $result[] = $totalRows_rsSubTask > 0 ? true : false;
                                                    }
                                                }

                                                $query_rsTeamLeader = $db->prepare("SELECT role FROM tbl_projmembers WHERE role=2 AND projid=:projid");
                                                $query_rsTeamLeader->execute(array(":projid" => $projid));
                                                $totalRows_rsTeamLeader = $query_rsTeamLeader->rowCount();
                                                return !empty($result) && !in_array(false, $result)  && $totalRows_rsTeamLeader > 0 ? true : false;
                                            }

                                            $proceed = validate_team();
                                            if ($proceed) {
                                                $assigned_responsible = check_if_assigned($projid, $workflow_stage, $project_sub_stage, 1);
                                                $query_Output = $db->prepare("SELECT * FROM tbl_project_details p INNER JOIN tbl_indicator i ON i.indid = p.indicator WHERE projid = :projid AND indicator_mapping_type  <> 0");
                                                $query_Output->execute(array(":projid" => $projid));
                                                $total_Output = $query_Output->rowCount();
                                                $stage = $workflow_stage;
                                                if ($total_Output == 0) {
                                                    $stage = $workflow_stage + 1;

                                                    if ($baseline_survey == 0) {
                                                        $stage = $stage + 1;
                                                        if ($implementation_type == 1) {
                                                            $stage = $stage + 1;
                                                        }
                                                    }
                                                }

                                                $approve_details =
                                                    "{
                                                        get_edit_details: 'details',
                                                        projid:$projid,
                                                        workflow_stage:$stage,
                                                        project_directorate:$project_directorate,
                                                        project_name:'$projname',
                                                        sub_stage:'$project_sub_stage',
                                                    }";
                                                if ($assigned_responsible) {
                                                    if ($approval_stage) {
                                            ?>
                                                        <button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Approve</button>
                                                    <?php
                                                    } else {
                                                        $data_entry_details =
                                                            "{
                                                            get_edit_details: 'details',
                                                            projid:$projid,
                                                            workflow_stage:$workflow_stage,
                                                            project_directorate:$project_directorate,
                                                            project_name:'$projname',
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
                </div>
            </section>
            <!-- end body  -->

            <!-- Start Modal Item Edit -->
            <div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center" id="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <form class="form-horizontal" id="add_output" action="" method="POST">
                                                <?= csrf_token_html(); ?>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <label><?= $ministrylabel ?>*:</label>
                                                    <div class="form-line">
                                                        <select name="department_id" id="department_id" onchange="get_sections()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                            <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?>....</option>
                                                            <?php
                                                            $query_rsDepartments =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent =0 ORDER BY stid ASC");
                                                            $query_rsDepartments->execute();
                                                            $totalRows_rsDepartments = $query_rsDepartments->rowCount();
                                                            if ($totalRows_rsDepartments > 0) {
                                                                while ($row_rsDepartment = $query_rsDepartments->fetch()) {
                                                                    $sector = $row_rsDepartment['sector'];
                                                                    $sector_id = $row_rsDepartment['stid'];
                                                            ?>
                                                                    <option value="<?= $sector_id ?>"><?= $sector ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <label><?= $departmentlabel ?>*:</label>
                                                    <div class="form-line" id="">
                                                        <select name="sector_id" id="sector_id" onchange="get_directorate()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                            <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?> first....</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <label><?= $directoratelabel ?>*:</label>
                                                    <div class="form-line" id="">
                                                        <select name="directorate_id" id="directorate_id" onchange="get_members()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                            <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?> first....</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top:5px; margin-bottom:5px">
                                                    <label class="control-label">Member:</label>
                                                    <div class="form-line">
                                                        <select name="member" id="member" class="form-control" required="required">
                                                            <option value="">Select Member from list</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top:5px; margin-bottom:5px">
                                                    <label class="control-label">Role:</label>
                                                    <div class="form-line">
                                                        <select name="role" id="role" class="form-control" required="required">
                                                            <?php
                                                            $query_projrole = $db->prepare("SELECT * FROM `tbl_project_team_roles` WHERE active=1");
                                                            $query_projrole->execute();
                                                            $total_rows = $query_projrole->rowCount();
                                                            $role_input = '<option value="">Select Role</option>';
                                                            if ($total_rows > 0) {
                                                                while ($row_projrole = $query_projrole->fetch()) {
                                                                    $id = $row_projrole['id'];
                                                                    $role = $row_projrole['role'];
                                                                    $role_input .= '<option value="' . $id . '" >' . $role . '</option>';
                                                                }
                                                            }
                                                            echo $role_input;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:5px; margin-bottom:5px" id="task_div">

                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                <input type="hidden" name="store_technical_team" id="store_technical_team" value="store_technical_team">
                                                <input type="hidden" name="userid" id="userid" value="">
                                                <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->
                </div>
                <!-- /modal-content -->
            </div>
            <!-- /modal-dailog -->
<?php
        } else {
            $results =  restriction();
            echo $results;
        }
    } else {
        $results =  restriction();
        echo $results;
    }

    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script>
    const details = {
        ministry: '<?= $ministrylabel ?>',
        section: '<?= $departmentlabel ?>',
        directorate: '<?= $directoratelabel ?>',
    }
    const redirect_url = "add-project-team";
</script>
<script src="assets/js/team/index.js"></script>
<script src="assets/js/master/index.js"></script>