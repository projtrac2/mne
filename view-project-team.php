<?php
require('includes/head.php');
if ($permission) {
    try {
        $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, p.projstage, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.deleted='0' AND projstage > 5 AND (p.projstatus=0 OR p.projstatus=4 OR p.projstatus=3 OR p.projstatus=11)");
        $query_rsProjects->execute();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
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
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example " id="manageItemTable">
                                    <thead>
                                        <tr style="background-color:#0b548f; color:#FFF">
                                            <th style="width:4%" align="center">#</th>
                                            <th style="width:12%">Project Code</th>
                                            <th style="width:50%">Project Name </th>
                                            <th style="width:10%">Project Stage </th>
                                            <th style="width:20">Project Department</th>
                                            <th style="width:9%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalRows_rsProjects > 0) {
                                            $counter = 0;
                                            while ($row_rsProjects = $query_rsProjects->fetch()) {
                                                $projid = $row_rsProjects['projid'];
                                                $progid = $row_rsProjects['progid'];
                                                $projsector = $row_rsProjects['projsector'];
                                                $department = $row_rsProjects['sector'];
                                                $projstageid = $row_rsProjects['projstage'];

                                                $query_projsector = $db->prepare("SELECT * FROM tbl_sectors WHERE stid = :sector");
                                                $query_projsector->execute(array(":sector" => $projsector));
                                                $row_projsector = $query_projsector->fetch();
                                                $sector = $row_projsector['sector'];

                                                $query_projteam = $db->prepare("SELECT * FROM tbl_projmembers WHERE projid = :projid");
                                                $query_projteam->execute(array(":projid" => $projid));
                                                $row_projteam = $query_projteam->fetch();
                                                $totalRows_projteam = $query_projteam->rowCount();

                                                $query_projstage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE priority = :projstageid");
                                                $query_projstage->execute(array(":projstageid" => $projstageid));
                                                $row_projstage = $query_projstage->fetch();
                                                $projstage = $row_projstage ? $row_projstage['stage'] : '';

                                                // if($totalRows_projteam < 6){
                                                $query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
                                                $query_rsPrograms->execute(array(":progid" => $progid));
                                                $row_rsPrograms = $query_rsPrograms->fetch();
                                                $totalRows_rsPrograms = $query_rsPrograms->rowCount();

                                                $project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
                                                $project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
                                                $project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";

                                                $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                if ($filter_department) {
                                                    $counter++;
                                        ?>
                                                    <tr class="projects">
                                                        <td align="center"><?= $counter ?></td>
                                                        <td><?php echo $row_rsProjects['projcode'] ?></td>
                                                        <td><?php echo $row_rsProjects['projname'] ?></td>
                                                        <td><?php echo $projstage ?></td>
                                                        <td><?php echo $department ?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <!-- <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="get_team(<?php echo $row_rsProjects['projid'] ?>, 1)">
                                                                            <i class="fa fa-file-text"></i> View Team
                                                                        </a> -->
                                                                        <a href="/edit-team.php?projid=<?php echo base64_encode('encodeprocprj'.$projid); ?>">
                                                                            <i class="fa fa-pencil-square"></i> </i> View Team
                                                                        </a>
                                                                    </li>
                                                                    <?php
                                                                    if (in_array("updates", $page_actions)) {
                                                                    ?>
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="getChangeProjTeam(<?php echo $row_rsProjects['projid'] ?>)">
                                                                                <i class="fa fa-pencil-square"></i> </i> Ammend Team
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
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7">No Approved projects without team</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> Project Team Members</h4>
                </div>
                <div class="modal-body" id="moreinfo">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr class="bg-grey">
                                    <th width="8%"><strong>Photo</strong></th>
                                    <th width="32%"><strong>Fullname</strong></th>
                                    <th width="15%"><strong>Designation</strong></th>
                                    <th width="10%"><strong>Role</strong></th>
                                    <th width="10%"><strong>Availability</strong></th>
                                    <th width="15%"><strong>Email</strong></th>
                                    <th width="10%"><strong>Phone</strong></th>
                                </tr>
                            </thead>
                            <tbody id="technical_team">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Item more -->

    <!-- add item -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="teamformsubmit" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal">
                            <div id="title"><i class="fa fa-plus"></i> Ammend Project Team</div>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="teamForm">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->
                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="newitem" id="newitem" value="new">
                            <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="team-form-submit" value="Save" />
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->
<?php
} else {
    var_dump("sorry this is what we should work on the data ");
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script type="text/javascript">
    const get_team = projid => {
        if (projid != '') {
            $.ajax({
                type: "get",
                url: "ajax/team/project",
                data: {
                    get_team_members: "get_team_members",
                    projid: projid
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#technical_team").html(response.technical_team);
                        $("#mne_team").html(response.mne_team);
                    } else {
                        error_alert("Sorry no users found");
                    }
                }
            });
        }
    }
</script>