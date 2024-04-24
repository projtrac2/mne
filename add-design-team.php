<?php 
require('includes/head.php');
if ($permission) {
    try {
        $edit = false;
        $design_id = isset($_GET['design_id']) ? base64_decode($_GET['design_id']) : "";
        $site_id = isset($_GET['site_id']) ? base64_decode($_GET['site_id']) : "";

        $query_rsDesigns = $db->prepare("SELECT p.id, p.projid  FROM tbl_project_output_designs d INNER JOIN tbl_project_details p ON p.id = d.output_id WHERE d.id=:design_id ORDER BY d.id");
        $query_rsDesigns->execute(array(":design_id" => $design_id));
        $row_rsDesigns = $query_rsDesigns->fetch();
        $totalRows_rsDesigns = $query_rsDesigns->rowCount();

        $projid = $totalRows_rsDesigns > 0 ? $row_rsDesigns['projid'] : null;
        $outputid = $totalRows_rsDesigns > 0 ? $row_rsDesigns['id'] : null;


        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");
        $query_rsProjects->execute();
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $projname = $totalRows_rsProjects > 0 ? $row_rsProjects['projname'] : "";
        $projcode = $totalRows_rsProjects > 0 ? $row_rsProjects['projcode'] : "";

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "store_general")) {
            $roles = $_POST['role'];
            $members = $_POST['member'];
            $total_members = count($members);
            for ($i = 0; $i < $total_members; $i++) {
                $roleid = $roles[$i];
                $ptid = $members[$i];
                $insertSQL = $db->prepare("INSERT INTO tbl_projmembers (projid,ptid, role, dateentered, user_name) VALUES (:ptid, :projid, :role, :datecreated, :createdby)");
                $results = $insertSQL->execute(array(':projid' => $projid, ':ptid' => $ptid, ':role' => $role[$i], ':datecreated' => $current_date, ':createdby' => $user_name));
                send_email($projid, $ptid, $roleid);
            }
        }
    } catch (PDOException $ex) {
        $result = "An error occurred: " . $ex->getMessage();
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
                            <button onclick="history.back()" class="btn btn-primary"> Go Back</button>
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
                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->
                            <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                        <label class="control-label">Project Code:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                        <label class="control-label">Project Name:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" value=" <?= $projname ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-users" aria-hidden="true"></i> Team Details</legend>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="files_table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">#</th>
                                                    <th style="width:25%">Member *</th>
                                                    <th style="width:25%">Department *</th>
                                                    <th style="width:25%">Sector</th>
                                                    <th style="width:15%">Sector</th>
                                                    <th style="width:5%">
                                                        <button type="button" name="addplus" id="add_row_gen" data-toggle="modal" data-target="#outputItemModal" onclick="get_tasks({design_id:<?= $design_id ?>, user_id:'',ministry_id:'',sector_id:'', edit:'', role_id:''})" class="btn btn-success btn-sm">
                                                            <span class="glyphicon glyphicon-plus">
                                                            </span>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="financier_table_body">
                                                <tr></tr>
                                                <?php
                                                $query_rsMembers = $db->prepare("SELECT * FROM `tbl_projmembers` WHERE projid=:projid  AND role_type = 2  GROUP BY ptid");
                                                $query_rsMembers->execute(array(":projid" => $projid));
                                                $total_rsMembers = $query_rsMembers->rowCount();

                                                if ($total_rsMembers > 0) {
                                                    $rowno = 0;
                                                    while ($row_rsMembers = $query_rsMembers->fetch()) {
                                                        $rowno++;
                                                        $role_id = $row_rsMembers['role'];
                                                        $ptid = $row_rsMembers['ptid'];

                                                        $query_teams = $db->prepare("SELECT * FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE userid=:ptid");
                                                        $query_teams->execute(array(":ptid" => $ptid));
                                                        $row_teams = $query_teams->fetch();

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

                                                        $details = "{
                                                            design_id:$design_id,
                                                            user_id:$ptid,
                                                            ministry_id:$department_id,
                                                            sector_id:$section_id,
                                                            edit:'1', 
                                                            role_id:$role_id
                                                        }";
                                                ?>
                                                        <tr id="finrow<?= $rowno ?>">
                                                            <td><?= $rowno  ?></td>
                                                            <td><?= $membername  ?></td>
                                                            <td><?= $department  ?></td>
                                                            <td><?= $sector  ?></td>
                                                            <td><?= $role  ?></td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm" id="delete" data-toggle="modal" data-target="#outputItemModal" onclick="get_tasks(<?= $details ?>)">
                                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr id="removeTr">
                                                        <td colspan="5">Add Team Member</td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </form>
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
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <form class="form-horizontal" id="add_output" action="" method="POST">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                            <label class="control-label">Ministry :</label>
                                            <div class="form-line">
                                                <select onchange="get_sectors('row1', '', '')" name="departments" id="departmentsrow1" class="form-control" required="required">
                                                    <?php
                                                    $query_department = $db->prepare("SELECT * FROM `tbl_sectors` WHERE parent =0 ");
                                                    $query_department->execute();
                                                    $row_department = $query_department->fetch();
                                                    $input = '<option value="">Select Department</option>';

                                                    if ($row_department) {
                                                        do {
                                                            $id = $row_department['stid'];
                                                            $sector = $row_department['sector'];
                                                            $input .= '<option value="' . $id . '">' . $sector . '</option>';
                                                        } while ($row_department = $query_department->fetch());
                                                    }

                                                    echo $input;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                            <label class="control-label">Sector:</label>
                                            <div class="form-line">
                                                <select onchange='get_members("row1", "", "")' name="sector" id="sectorsrow1" class="form-control" required="required">
                                                    <option value="">Select Sector from list</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                            <label class="control-label">Member:</label>
                                            <div class="form-line">
                                                <select name="member" id="membersrow1" class="form-control" required="required">
                                                    <option value="">Select Member from list</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                            <label class="control-label">Role:</label>
                                            <div class="form-line">
                                                <select name="role" id="rolesrow1" class="form-control" required="required">
                                                    <?php
                                                    $query_projrole = $db->prepare("SELECT * FROM `tbl_project_team_roles` WHERE active=1");
                                                    $query_projrole->execute();
                                                    $row_projrole = $query_projrole->fetch();
                                                    $role_input = '<option value="">Select Role</option>';
                                                    if ($row_projrole) {
                                                        do {
                                                            $id = $row_projrole['id'];
                                                            $role = $row_projrole['role'];
                                                            $role_input .= '<option value="' . $id . '" >' . $role . '</option>';
                                                        } while ($row_projrole = $query_projrole->fetch());
                                                    }
                                                    echo $role_input;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="files_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%;">#</th>
                                                            <th style="width:95%">Task *</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tasks_table_body">
                                                        <tr></tr>
                                                        <tr id="removeTr">
                                                            <td colspan="2">Add Team Member</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                <input type="hidden" name="design_id" id="design_id" val`ue="<?= $design_id ?>">
                                                <input type="hidden" name="store_tasks" id="store_tasks" value="store_tasks">
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
    </div>
<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>

<script>
    const ajax_url = "ajax/team/index";
    $(document).ready(function() {
        $("#add_output").submit(function(e) {
            e.preventDefault();
            var form_data = $(this).serialize();
            var checked = $("[name='tasks[]']:checked").length > 0;
            if (checked) {
                $.ajax({
                    type: "post",
                    url: ajax_url,
                    data: form_data,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            success_alert("Record successfully created");
                        } else {
                            error_alert("Record could not be created");
                        }

                        $(".modal").each(function() {
                            $(this).modal("hide");
                            $(this)
                                .find("form")
                                .trigger("reset");
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    }
                });
            } else {
                error_alert("Please ensure you have selected at least one task");
            }
        });
    });

    function get_sectors(rowno, department_id = "", sector_id = "") {
        var department_id = department_id != "" ? department_id : $(`#departments${rowno}`).val();
        $(`#sectorsrow1`).html("");

        if (department_id != '') {
            $.ajax({
                type: "get",
                url: ajax_url,
                data: {
                    get_sectors: "get_sectors",
                    department_id: department_id
                },
                dataType: "json",
                success: function(response) {
                    $(`#sectorsrow1`).html(response.sectors);
                    $(`#sectorsrow1`).val(sector_id);
                }
            });
        }
    }

    function get_members(rowno, section_id = "", member_id = "") {
        var section_id = section_id != "" ? section_id : $(`#sectorsrow1`).val();
        if (section_id != '') {
            $.ajax({
                type: "get",
                url: ajax_url,
                data: {
                    get_members: "get_members",
                    section_id: section_id
                },
                dataType: "json",
                success: function(response) {
                    $(`#membersrow1`).html(response.members);
                    $("#membersrow1").val(member_id);
                }
            });
        }
    }


    function get_tasks(details) {
        var user_id = details.user_id;
        var design_id = details.design_id;
        var edit = details.edit;
        var ministry_id = details.ministry_id;
        var sector_id = details.sector_id;
        var role_id = details.role_id;

        $("#departmentsrow1").val("");
        $("#rolesrow1").val("");
        $("#sectorsrow1").html('<option value="">Ministry First</option>');
        $("#membersrow1").html('<option value="">Select Member from list</option>');

        $("#userid").val(user_id);
        if (edit == "1") {
            get_sectors('row1', ministry_id, sector_id);
            get_members(1, sector_id, user_id);

            $("#departmentsrow1").val(ministry_id);
            $("#rolesrow1").val(role_id);
        }



        if (design_id != "") {
            $.ajax({
                type: "get",
                url: ajax_url,
                data: {
                    get_tasks: "get_tasks",
                    design_id: design_id,
                    user_id: user_id,
                },
                dataType: "json",
                success: function(response) {
                    $("#tasks_table_body").html(response.tasks);
                }
            });
        }
    }
</script>