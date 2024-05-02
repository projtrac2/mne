<?php
try {
    require('includes/head.php');
    if ($permission) {
        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage ORDER BY p.projid DESC");
        $query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
        $totalRows_rsProjects = $query_rsProjects->rowCount();

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
                                            <tr id="colrow">
                                                <th style="width:5%" align="center">#</th>
                                                <th style="width:10%">Project Code</th>
                                                <th style="width:55%">Project Name </th>
                                                <th style="width:25">Project Status</th>
                                                <th style="width:5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($totalRows_rsProjects > 0) {
                                                $counter = 0;
                                                while ($row_rsProjects = $query_rsProjects->fetch()) {
                                                    $projid = $row_rsProjects['projid'];
                                                    $projid_hashed = base64_encode("projrisk047{$projid}");
                                                    $implementation = $row_rsProjects['projcategory'];
                                                    $sub_stage = $row_rsProjects['proj_substage'];
                                                    $project_department = $row_rsProjects['projsector'];
                                                    $project_section = $row_rsProjects['projdept'];
                                                    $project_directorate = $row_rsProjects['directorate'];
                                                    $projname = $row_rsProjects['projname'];
                                                    $projcode = $row_rsProjects['projcode'];
                                                    $projstatusid = $row_rsProjects['projstatus'];
                                                    $start_date = date('Y-m-d');

                                                    if ($projstatusid == 5) {
                                                        $statuslabelcolor = "label-success";
                                                    } elseif ($projstatusid == 4) {
                                                        $statuslabelcolor = "label-primary";
                                                    } elseif ($projstatusid == 3) {
                                                        $statuslabelcolor = "label-info";
                                                    } elseif ($projstatusid == 11) {
                                                        $statuslabelcolor = "label-danger";
                                                    } elseif ($projstatusid == 6) {
                                                        $statuslabelcolor = "label-pink";
                                                    } elseif ($projstatusid == 2) {
                                                        $statuslabelcolor = "label-brown";
                                                    }

                                                    $query_project_status = $db->prepare("SELECT statusname FROM tbl_status WHERE statusid=:projstatusid");
                                                    $query_project_status->execute(array(":projstatusid" => $projstatusid));
                                                    $row_project_status = $query_project_status->fetch();
                                                    $project_status = $row_project_status["statusname"];

                                                    $query_proj_risks = $db->prepare("SELECT * FROM tbl_project_risks r left join tbl_risk_register g on g.id=r.risk_id left join tbl_projrisk_categories c on c.catid=g.risk_category WHERE projid=:projid GROUP BY r.id");
                                                    $query_proj_risks->execute(array(":projid" => $projid));
                                                    $totalRows_proj_risks = $query_proj_risks->rowCount();

                                                    $filter_department = view_record($project_department, $project_section, $project_directorate);

                                                    $details = "{
                                                        get_edit_details: 'details',
                                                        projid:$projid,
                                                        workflow_stage:$workflow_stage,
                                                        sub_stage:$sub_stage,
                                                        project_directorate:$project_directorate,
                                                        project_name:'$projname',
                                                    }";
                                                    $assigned_responsible = check_if_assigned($projid, $workflow_stage, $sub_stage, 1);
                                                    $assign_responsible = (in_array("assign_data_entry_responsible", $page_actions) && $sub_stage == 0) || (in_array("assign_approval_responsible", $page_actions) && $sub_stage == 2) ? true : false;
                                                    if ($filter_department) {
                                                        $counter++;
                                            ?>
                                                        <tr>
                                                            <td align="center"><?= $counter ?></td>
                                                            <td><?= $projcode ?></td>
                                                            <td><?= $projname ?></td>
                                                            <td><label class='label <?= $statuslabelcolor ?>'><?= $project_status; ?></label></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="project_info(<?= $projid ?>)">
                                                                                <i class="fa fa-file-text"></i> View More
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                        if ($assigned_responsible) {
                                                                        ?>
                                                                            <li>
                                                                                <a type="button" id="#finishAddItemModalBtn" href="project-risks-monitoring.php?proj=<?= $projid_hashed ?>" title="Click here to monitor Project Risk Plan">
                                                                                    <i class="fa fa-check"></i> Monitor Risk Plan
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
<script src="assets/js/projects/view-project.js"></script>
<script src="assets/js/master/index.js"></script>