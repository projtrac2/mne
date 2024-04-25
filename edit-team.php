<?php
try {
    require('includes/head.php');
    if ($permission && (isset($_GET['projid']) && !empty($_GET["projid"]))) {
        $edit = false;
        $project_team_stage = 9;
        $decode_projid =   base64_decode($_GET['projid']);
        $projid_array = explode("encodeprocprj", $decode_projid);
        $projid = $projid_array[1];


        $query_rsProjects = $db->prepare("SELECT p.*, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid  WHERE p.deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $projname = $workflow_stage = $sub_stage = $projcode = $project_directorate = $project_sub_stage = '';


        if ($totalRows_rsProjects > 0) {
            $projname = $row_rsProjects['projname'];
            $workflow_stage =  $row_rsProjects['projstage'];
            $sub_stage = $row_rsProjects['proj_substage'];
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
                                                    $query_rsMembers = $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid  AND stage=:workflow_stage and team_type=4");
                                                    $query_rsMembers->execute(array(":projid" => $projid, ":workflow_stage" => 9));
                                                    $total_rsMembers = $query_rsMembers->rowCount();
                                                    if ($total_rsMembers > 0) {
                                                        $rowno = 0;
                                                        while ($row_rsMembers = $query_rsMembers->fetch()) {
                                                            $rowno++;
                                                            $role_id = $row_rsMembers['role'];
                                                            $ptid = $row_rsMembers['responsible'];
                                                            $created_by = $row_rsMembers['created_by'];
                                                            $get_user = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid=:user_id ");
                                                            $get_user->execute(array(":user_id" => $ptid));
                                                            $count_user = $get_user->rowCount();
                                                            $row_teams = $get_user->fetch();

                                                            $department_id = $row_teams ? $row_teams['ministry'] : "";
                                                            $section_id = $row_teams ? $row_teams['department'] : "";
                                                            $fname = $row_teams ? $row_teams['fullname'] : "";
                                                            $title_id = $row_teams ? $row_teams['title'] : "";
                                                            $directorate_id = $row_teams['directorate'];

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
                                                                                <a href="#" data-toggle="modal" data-target="#editRole" onclick="change_role_modal_data(<?= $ptid ?>,<?= $role_id ?>, <?= $projid ?>,'<?= $f_name ?>')">
                                                                                    <i class="fa fa-id-card-o"></i> Change Role
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#replaceMember" onclick="get_members_replace(<?= $department_id ?>, <?= $section_id ?>, <?= $directorate_id ?>, <?= $ptid ?>, '<?= $f_name ?>', <?= $role_id ?>, <?= $created_by ?>)">
                                                                                    <i class="fa fa-recycle"></i> Replace User
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#editChecklist" onclick="add_team_members(<?= $ptid ?>, <?= $role_id ?>)">
                                                                                    <i class="fa fa-pencil-square-o"></i> Edit Checklist
                                                                                </a>
                                                                            </li>
                                                                            <!-- <li>
                                                                        <a type="button" data-toggle="modal" data-target="#outputItemModal" id="assignModalBtn" onclick="add_team_members(<?= $ptid ?>, <?= $role_id ?>)">
                                                                            <i class="fa fa-pencil"></i> Edit
                                                                        </a>
                                                                    </li> -->
                                                                            <li>
                                                                                <a type="button" onclick="delete_team_member(<?= $details ?>)" id="addFormModalBtn">
                                                                                    <i class="fa fa-trash"></i> Remove Member
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
                                                <input type="hidden" name="store_technical_team" id="store_technical_team" value="4">
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

            <!-- edit role -->
            <div class="modal fade" id="editRole" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center" id="modal-title">Change role for <span id="change_role_user_name"></span></h4>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <form class="form-horizontal" id="add_output_role" action="" method="POST">
                                                <?= csrf_token_html(); ?>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:5px; margin-bottom:5px">
                                                    <label class="control-label">Role:</label>
                                                    <div class="form-line">
                                                        <select name="role" id="role_edit_select" class="form-control" required="required">

                                                        </select>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                <input type="hidden" name="store_technical_team" id="store_technical_team" value="1">
                                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                <input type="hidden" name="member" id="userid_change_role">
                                                <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-role" value="Save" />
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
            <!-- edit role -->

            <!-- edit checklist -->
            <div class="modal fade" id="editChecklist" tabindex="-1" role="dialog">
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
                                        <form class="form-horizontal" id="add_output_check_list" action="" method="POST">
                                            <?= csrf_token_html(); ?>

                                            <div class="body">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:5px; margin-bottom:5px" id="task_div_edit">

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                    <input type="hidden" name="store_technical_team" id="store_technical_team" value="3">
                                                    <input type="hidden" name="member" id="userid_edit_checklist">
                                                    <input type="hidden" name="role" id="role_id">
                                                    <input name="submit" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-check-list" value="Save" />
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
            <!-- edit checklist -->

            <!-- replace member -->
            <div class="modal fade" id="replaceMember" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center" id="modal-title">Replace Member</h4>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="padding-left: 5%; padding-right: 5%; padding-top: 2%; padding-bottom: 2%;">
                                        <div class="body">

                                            <p>Replace <span id="replace_user"></span> with:</p>
                                            <select name="owner" class="form-control owner" id="replace-input">

                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                <input type="hidden" name="store_technical_team_replace" id="store_technical_team_replace" value="2">
                                                <input type="hidden" name="role_id" id="role_id">
                                                <input type="hidden" name="ptid" id="ptid">
                                                <input type="hidden" name="created_by" id="created_by">
                                                <input name="submtt" onclick="save_replace()" type="button" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- replace member -->
<?php
        } else {
            $results =  restriction();
            echo $results;
        }
    } else {
        $results =  restriction();
        echo $results;
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
require('includes/footer.php');
?>
<script>
    const details = {
        ministry: '<?= $ministrylabel ?>',
        section: '<?= $departmentlabel ?>',
        directorate: '<?= $directoratelabel ?>',
    }
    const redirect_url = "add-project-team";
</script>
<script src="assets/js/team/team.js"></script>
<script src="assets/js/master/index.js"></script>