<?php
require('includes/head.php');
if ($permission) {
    try {
        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage ORDER BY p.projid DESC");
        $query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
        $totalRows_rsProjects = $query_rsProjects->rowCount();
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
                                            <th style="width:60%">Project </th>
                                            <th style="width:10">Due Date</th>
                                            <th style="width:10">Status</th>
                                            <th style="width:5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalRows_rsProjects > 0) {
                                            $counter = 0;
                                            while ($row_rsProjects = $query_rsProjects->fetch()) {
                                                $counter++;

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

                                                $query_rsDesigns = $db->prepare("SELECT * FROM tbl_project_output_designs s INNER JOIN tbl_project_details d ON d.id = s.output_id  WHERE projid=:projid");
                                                $query_rsDesigns->execute(array(":projid" => $projid));
                                                $totalRows_rsDesigns = $query_rsDesigns->rowCount();


                                                $assigned_responsible = check_if_assigned($projid, $workflow_stage, $sub_stage, 1);
                                                $assign_responsible = (in_array("assign_data_entry_responsible", $page_actions) && $sub_stage == 0) || (in_array("assign_approval_responsible", $page_actions) && $sub_stage == 2) ? true : false;

                                                $details = "{
														get_edit_details: 'details',
														projid:$projid,
														workflow_stage:$workflow_stage,
														sub_stage:$sub_stage,
														project_directorate:$project_directorate,
													}";

                                                $query_rsMapping = $db->prepare("SELECT * FROM tbl_markers  WHERE projid=:projid ");
                                                $query_rsMapping->execute(array(":projid" => $projid));
                                                $total_rsMapping = $query_rsMapping->rowCount();

                                                $today = date('Y-m-d');
                                                $assigned = ($sub_stage == 3 || $sub_stage == 1) ? true : false;
                                                $activity = "Add";
                                                if ($total_rsMapping > 0) {
                                                    $activity = $sub_stage > 1 ? "Approve"  : "Edit";
                                                }

                                                $due_date = get_master_data_due_date($projid, $workflow_stage, $sub_stage);
                                                $activity_status = "Pending";
                                                if ($sub_stage > 1) {
                                                    $activity_status = "Pending Approval";
                                                } else if ($sub_stage < 2) {
                                                    $activity_status = $sub_stage == 1 ?  "Assigned" : "Pending";
                                                    if ($today > $due_date) {
                                                        $activity_status = "Behind Schedule";
                                                    }
                                                }
                                        ?>
                                                <tr>
                                                    <td align="center"><?= $counter ?></td>
                                                    <td><?= $projcode ?></td>
                                                    <td><?= $projname ?></td>
                                                    <td><?= date('Y M d', strtotime($due_date)) ?></td>
                                                    <td><label class='label label-success'><?= $activity_status; ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="project_info(<?= $projid ?>)">
                                                                        <i class="fa fa-file-text"></i> Project Info
                                                                    </a>
                                                                </li>
                                                                <?php
                                                                if ($assign_responsible) {
                                                                ?>
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" data-target="#assign_modal" id="assignModalBtn" onclick="get_responsible_options(<?= $details ?>)">
                                                                            <i class="fa fa-users"></i> Assign
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                if ($assigned_responsible) {
                                                                ?>
                                                                    <li>
                                                                        <a type="button" href="add-project-mapping.php?projid=<?= $projid_hashed ?>">
                                                                            <i class="fa fa-file-text"></i> <?= $activity ?> Map Project
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
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
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
    <!-- End Item more -->

    <!-- Start Modal Item approve -->
    <div class="modal fade" id="assign_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Assign Project</h4>
                </div>
                <form class="form-horizontal" id="assign_responsible" action="" method="POST">
                    <div class="modal-body" style="max-height:450px; overflow:auto;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="projduration">Responsible *:</label>
                            <div class="form-line">
                                <select name="responsible" id="responsible" class="form-control" required="required">

                                </select>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->
                    <div class="modal-footer approveItemFooter">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="projid" id="projid" value="">
                            <input type="hidden" name="workflow_stage" id="workflow_stage" value="<?= $workflow_stage ?>">
                            <input type="hidden" name="sub_stage" id="sub_stage" value="">
                            <input type="hidden" name="assign_responsible" id="assign_responsible" value="new">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Assign" />
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>

    <!-- add item -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="submit_assign_form" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Assign </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active"> Project: <span id="project_name"></span> </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label>Team*:</label>
                                <div class="form-line">
                                    <select name="team[]" multiple id="team" onchange="get_responsible()" class="form-control selectpicker show-tick" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                        <option value="">Select Responsible Team</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label>Responsible*:</label>
                                <div class="form-line" id="responsible1">
                                    <select name="responsible" id="m_responsible" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px" required>
                                        <option value="" selected="selected" class="selection">....Select Team first....</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label for="program_target" class="control-label">Mapping Date *:</label>
                                <div class="form-input">
                                    <input type="date" name="mapping_date" id="mapping_date" onchange="validate_date()" placeholder="Enter" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="projid" id="m_projid" value="">
                            <input type="hidden" name="workflow_stage" id="m_workflow_stage" value="<?= $workflow_stage ?>">
                            <input type="hidden" name="sub_stage" id="m_sub_stage" value="">
                            <input type="hidden" name="assign_mapping_responsible" id="assign_mapping_responsible" value="new">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-mapping" value="Assign" />
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
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/js/projects/view-project.js"></script>
<script src="assets/js/master/index.js"></script>