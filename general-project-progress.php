<?php
require('includes/head.php');
if ($permission) {
    try {
        $workflow_stage = 9;
        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage AND proj_substage >= 1  ORDER BY p.projid DESC");
        $query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));

        $totalRows_rsProjects = $query_rsProjects->rowCount();

        function get_role()
        {
            global $db;
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
            return  $role_input;
        }

        function get_members()
        {
            global $db;
            $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title ORDER BY ptid ASC");
            $query_rsPMbrs->execute();
            $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
            $input = '<option value="">Select Member from List</option>';
            if ($count_row_rsPMbrs > 0) {
                while ($row_rsPMbrs = $query_rsPMbrs->fetch()) {
                    $fullname = $row_rsPMbrs['ttitle'] . " " . $row_rsPMbrs['fullname'];
                    $user_id = $row_rsPMbrs['userid'];
                    $input .= '<option value="' . $user_id . '" >' . $fullname . '</option>';
                }
            }

            return $input;
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon . ' ' . $pageTitle ?>
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
                                    <thead>
                                        <tr style="background-color:#0b548f; color:#FFF">
                                            <th style="width:5%" align="center">#</th>
                                            <th style="width:10%">Code</th>
                                            <th style="width:50%">Project </th>
                                            <th style="width:15">Due Date</th>
                                            <th style="width:15">Status</th>
                                            <th style="width:5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalRows_rsProjects > 0) {
                                            $counter = 0;
                                            while ($row_rsProjects = $query_rsProjects->fetch()) {
                                                $projid = $row_rsProjects['projid'];
                                                $projid_hashed = base64_encode("projid54321{$projid}");
                                                $implementation = $row_rsProjects['projcategory'];
                                                $sub_stage = $row_rsProjects['proj_substage'];
                                                $project_department = $row_rsProjects['projsector'];
                                                $project_section = $row_rsProjects['projdept'];
                                                $project_directorate = $row_rsProjects['directorate'];
                                                $projname = $row_rsProjects['projname'];
                                                $projcode = $row_rsProjects['projcode'];
                                                $start_date = date('Y-m-d');

                                                $query_rsTeamMembers = $db->prepare("SELECT * FROM tbl_projmembers WHERE team_type=5 AND projid=:projid");
                                                $query_rsTeamMembers->execute(array(":projid" => $projid));
                                                $totalRows_rsTeamMembers = $query_rsTeamMembers->rowCount();

                                                $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid");
                                                $query_rsQuestions->execute(array(":projid" => $projid));
                                                $totalRows_rsQuestions = $query_rsQuestions->rowCount();

                                                $details = "{
                                                    projid:$projid,
                                                    projcode:'$projcode',
                                                    project_name:'$projname',
                                                    assign:$totalRows_rsTeamMembers,
                                                    edit:$totalRows_rsQuestions ,
                                                }";

                                                $counter++;
                                                $activity_status = $activity = '';
                                                $activity = $totalRows_rsTeamMembers == 0 ? "Assign" : "Reassign";
                                                if ($sub_stage == 1) {
                                                    $activity_status = "Pending Assignment";
                                                } else if ($sub_stage == 2) {
                                                    $activity_status = "Pending Checklist";
                                                } else if ($sub_stage == 3) {
                                                    $activity_status = "Pending Acceptance";
                                                } else {
                                                    $activity_status = "Issue";
                                                }
                                        ?>
                                                <tr>
                                                    <td align="center"><?= $counter ?></td>
                                                    <td><?= $projcode ?></td>
                                                    <td><?= $projname ?></td>
                                                    <td><?= date('Y M d') ?></td>
                                                    <td><label class='label label-success'><?= $activity_status; ?></label></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreModalBtn" onclick="project_info(<?= $projid ?>)">
                                                                        <i class="fa fa-file-text"></i> View More
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a type="button" data-toggle="modal" data-target="#assign_modal" id="addFormModalBtn" onclick="assign_committee(<?= $details ?>)">
                                                                        <i class="fa fa-users"></i> <?= $activity ?> Commitee
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="./add-inspection-acceptance-checklist.php?projid=<?= $projid_hashed ?>">
                                                                        <i class="fa fa-plus-square"></i> <?= $totalRows_rsQuestions > 0 ? "Edit" : "Add" ?> Inspection Checklist
                                                                    </a>
                                                                </li>
                                                                <?php
                                                                if ($sub_stage == 3) {
                                                                ?>
                                                                    <li>
                                                                        <a type="button" href="project-inspection.php?projid=<?= $projid_hashed ?>">
                                                                            <i class="fa fa-list"></i> Inspect
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                            // }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end body  -->
    <!-- Start Modal Item approve -->
    <div class="modal fade" id="assign_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Assign Project</h4>
                </div>
                <form class="form-horizontal" id="assign_responsible" action="" method="POST">
                    <div class="modal-body">
                        <fieldset class="scheduler-border" id="tasks_div">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> Assign Commitee </legend>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active">Project Name: <span id="project_name"></span> </li>
                                    <li class="list-group-item"><strong>Code: </strong> <span id="project_code"></span> </li>
                                </ul>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="projoutputTable">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="member_table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="70%">Member</th>
                                                <th width="20%">Role</th>
                                                <th width="5%">
                                                    <button type="button" name="addplus" id="addplus_output" onclick="add_row_member();" class="btn btn-success btn-sm addplus_output">
                                                        <span class="glyphicon glyphicon-plus">
                                                        </span>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="member_table_body">
                                            <tr id="m_row0">
                                                <td>1</td>
                                                <td>
                                                    <select name="member[]" id="memberrow0" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                        <?= get_members() ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="role[]" id="rolerow0" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                        <?= get_role() ?>
                                                    </select>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <!-- /modal-body -->
                    <div class="modal-footer approveItemFooter">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="projid" id="member_projid" value="">
                            <input type="hidden" name="assign_responsible" id="assign_responsible" value="new">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-2" value="Assign" />
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>
    <!-- end assignment modal -->

    <!-- Start Modal Item approve -->
    <div class="modal fade" id="inspection_acceptance_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Project Inspection Checklist</h4>
                </div>
                <form class="form-horizontal" id="add_questions_form" action="" method="POST">
                    <div class="modal-body">
                        <fieldset class="scheduler-border" id="tasks_div">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Inspection & Acceptance Checklists </legend>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active">Project Name: <span id="projname"></span> </li>
                                    <li class="list-group-item"><strong>Code: </strong> <span id="projcode"></span> </li>
                                </ul>
                            </div>
                            <div class="col-md-12" id="projoutputTable">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="question_table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="90%">Question</th>
                                                <th width="5%">
                                                    <button type="button" name="addplus" id="addplus_output" onclick="add_row_checklist();" class="btn btn-success btn-sm addplus_output">
                                                        <span class="glyphicon glyphicon-plus">
                                                        </span>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="checklist_table_body">
                                            <tr id="m_row0">
                                                <td>1</td>
                                                <td>
                                                    <input type="text" name="question[]" id="questionrow0" placeholder="Enter Question" class="form-control" required />
                                                </td>
                                                <td> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </div> <!-- /modal-body -->
                    <div class="modal-footer approveItemFooter">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="projid" id="checklist_projid" value="">
                            <input type="hidden" name="add_questions" id="add_questions" value="new">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-1" value="Submit" />
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>
    <!-- end assignment modal -->

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
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/js/projects/view-project.js"></script>

<script>
    const members = <?= json_encode(get_members()) ?>;
    const roles = <?= json_encode(get_role()) ?>;
    var ajax_url1 = "ajax/inspection/general";

    $(document).ready(function() {
        $("#assign_responsible").submit(function(e) {
            e.preventDefault();
            $("#tag-form-submit-2").prop("disabled", true);
            $.ajax({
                type: "post",
                url: ajax_url1,
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        success_alert("Record saved successfully");
                    } else {
                        error_alert("Record could not be saved successfully");
                    }
                    $("#tag-form-submit-2").prop("disabled", false);
                    setTimeout(() => {
                        location.reload(true);
                    }, 3000);
                }
            });
        });

        $("#add_questions_form").submit(function(e) {
            e.preventDefault();
            $("#tag-form-submit-1").prop("disabled", true);
            $.ajax({
                type: "post",
                url: ajax_url1,
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        success_alert("Record saved successfully");
                    } else {
                        error_alert("Record could not be saved successfully");
                    }
                    $("#tag-form-submit-2").prop("disabled", false);
                    setTimeout(() => {
                        location.reload(true);
                    }, 3000);
                }
            });
        });
    });

    function add_checklist(details) {
        $("#checklist_projid").val(details.projid);
        $("#projcode").html(details.projcode);
        $("#projname").html(details.project_name);
        if (details.edit > 0) {
            $.ajax({
                type: "GET",
                url: ajax_url1,
                data: {
                    get_edit_questions: 'get_edit_questions',
                    projid: details.projid,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#checklist_table_body").html(response.questions);
                    } else {
                        error_alert("Record could not be saved successfully");
                    }
                }
            });
        }
    }

    const assign_committee = (details) => {
        $("#member_projid").val(details.projid);
        $("#project_code").html(details.projcode);
        $("#project_name").html(details.project_name);
        if (details.edit > 0) {
            $.ajax({
                type: "GET",
                url: ajax_url1,
                data: {
                    get_edit_members: 'get_edit_members',
                    projid: details.projid,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#member_table_body").html(response.members);

                    } else {
                        error_alert("Record could not be saved successfully");
                    }
                }
            });
        }
    }

    function add_row_member() {
        $row = $("#member_table_body tr").length;
        $row = $row + 1;
        var randno = Math.floor((Math.random() * 1000) + 1);
        var $rowno = $row + "" + randno;
        $("#member_table_body tr:last").after(`
        <tr id="mtng${$rowno}">
            <td></td>
            <td>
                <select name="member[]" id="memberrow${$rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
                    <option value="">..Select Member..</option>
                    ${members}
                </select>
            </td>
            <td>
                <select name="role[]" id="rolerow${$rowno}" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required>
                    <option value="">..Select Member..</option>
                    ${roles}
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_member("mtng${$rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>`);
        numbering_member();
    }

    function delete_member(rowno) {
        $("#" + rowno).remove();
        numbering_member();
    }

    function numbering_member() {
        $("#member_table_body tr").each(function(idx) {
            $(this)
                .children()
                .first()
                .html(idx + 1);
        });
    }

    function add_row_checklist() {
        $row = $("#checklist_table_body tr").length;
        $row = $row + 1;
        var randno = Math.floor((Math.random() * 1000) + 1);
        var $rowno = $row + "" + randno;

        $("#checklist_table_body tr:last").after(`
            <tr id="cht${$rowno}">
                <td></td>
                <td>
                    <input type="text" name="question[]" id="questionrow0" placeholder="Enter Question" class="form-control" required />
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"  onclick=delete_checklist("cht${$rowno}")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
            </tr>`);
        numbering_checklist();
    }

    function delete_checklist(rowno) {
        $("#" + rowno).remove();
        numbering_checklist();
    }

    function numbering_checklist() {
        $("#checklist_table_body tr").each(function(idx) {
            $(this)
                .children()
                .first()
                .html(idx + 1);
        });
    }
</script>