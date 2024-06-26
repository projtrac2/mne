<?php
require('includes/head.php');
if ($permission) {
    try {
        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage ORDER BY p.projid DESC");
        $query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $query_risk_impact =  $db->prepare("SELECT * FROM tbl_risk_impact WHERE active = 1");
        $query_risk_impact->execute();

        $query_risk_categories = $db->prepare("SELECT * FROM tbl_projrisk_categories");
        $query_risk_categories->execute();
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
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr style="background-color:#0b548f; color:#FFF">
                                                <th style="width:5%" align="center">#</th>
                                                <th style="width:10%">Code</th>
                                                <th style="width:50%">Project </th>
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
                                                    $projid = $row_rsProjects['projid'];
                                                    $projid_hashed = base64_encode("projid54321{$projid}");
                                                    $projname = $row_rsProjects['projname'];
                                                    $sub_stage = $row_rsProjects['proj_substage'];
                                                    $project_department = $row_rsProjects['projsector'];
                                                    $project_section = $row_rsProjects['projdept'];
                                                    $project_directorate = $row_rsProjects['directorate'];

                                                    $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                    $assigned_responsible = check_if_assigned($projid, $workflow_stage, $sub_stage, 1);
                                                    $assign_responsible = in_array("assign_data_entry_responsible", $page_actions) || in_array("assign_approval_responsible", $page_actions) ? true : false;

                                                    if ($filter_department) {
                                                        $counter++;
                                                        $today = date('Y-m-d');
                                                        $due_date = get_due_date($projid, $workflow_stage);
                                                        $activity_status = get_stage_status($projid, $workflow_stage, $sub_stage);
                                                        $details = "{
                                                                projid:$projid,
                                                                workflow_stage:$workflow_stage,
                                                                project_name:'$projname',
                                                            }";
                                            ?>
                                                        <tr>
                                                            <td align="center"><?= $counter ?></td>
                                                            <td><?php echo $row_rsProjects['projcode'] ?></td>
                                                            <td><?php echo $row_rsProjects['projname'] ?></td>
                                                            <td><?= date('Y M d', strtotime($due_date))  ?></td>
                                                            <td><?= $activity_status; ?></td>
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
                                                                                <a type="button" data-toggle="modal" data-target="#handOverItemModal" data-backdrop="static" data-keyboard="false" onclick="add_project_handover(<?= $details ?>)">
                                                                                    <i class="fa fa-exclamation-triangle text-danger"></i> Handover
                                                                                </a>
                                                                            </li>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="add_project_issues('<?= $projid_hashed ?>', '<?= htmlspecialchars($projname) ?>')">
                                                                                <i class="fa fa-exclamation-triangle text-danger"></i> Issues
                                                                            </a>
                                                                        </li>
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
        </section>
        <!-- end body  -->

        <!-- start issues modal  -->
        <div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <h3 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-warning" style="color:yellow"></i> <span id="modal_info"> PROJECT ISSUES</span></h3>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item list-group-item-action active">Project : <span id="project_name"></span> </li>
                            </ul>
                        </div>
                        <div class="card-header">
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#home"><i class="fa fa-pencil bg-orange" aria-hidden="true"></i> Record Issue &nbsp;<span class="badge bg-orange">|</span></a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1"><i class="fa fa-eye bg-blue" aria-hidden="true"></i> View Issues &nbsp;<span class="badge bg-blue">|</span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <form class="form-horizontal" id="add_items" action="" method="POST">
                                    <?= csrf_token_html(); ?>
                                    <fieldset class="scheduler-border" id="specification_issues">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> New Issue
                                        </legend>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                                <div class="form-inline">
                                                    <label for="">Issue Description</label>
                                                    <input name="issue_description" type="text" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:100%" placeholder="Describe the issue" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                                <div class="form-inline">
                                                    <label for="">Issue Area</label>
                                                    <div class="form-control" style="border:#CCC thin solid; border-radius:5px; width:100%">Others</div>
                                                    <input name="issue_area" type="hidden" value="1">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                                <div class="form-inline">
                                                    <label for="">Issue Impact</label>
                                                    <select name="issue_impact" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
                                                        <option value="">.... Select impact ....</option>
                                                        <?php
                                                        while ($row_risk_impact = $query_risk_impact->fetch()) {
                                                        ?>
                                                            <font color="black">
                                                                <option value="<?php echo $row_risk_impact['id'] ?>"><?php echo $row_risk_impact['description'] ?></option>
                                                            </font>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                                <div class="form-inline">
                                                    <label for="">Issue Priority</label>
                                                    <select name="issue_priority" class="form-control topic" data-live-search="true" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
                                                        <option value="" selected="selected" class="selection">... Select Issue Priority ...</option>
                                                        <option value="1" class="selection">High</option>
                                                        <option value="2" class="selection">Medium</option>
                                                        <option value="3" class="selection">Low</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
                                                <div class="form-inline">
                                                    <label for="">Risk Category</label>
                                                    <select name="risk_category" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
                                                        <option value="">.... Select Risk Category ....</option>
                                                        <?php
                                                        while ($row_risk_categories = $query_risk_categories->fetch()) {
                                                        ?>
                                                            <font color="black">
                                                                <option value="<?php echo $row_risk_categories['catid'] ?>"><?php echo $row_risk_categories['category'] ?></option>
                                                            </font>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="issue_type">
                                            </div>
                                        </div>
                                        <!-- Task Checklist Questions -->
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i> Attachments
                                        </legend>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:2%">#</th>
                                                                <th style="width:40%">Attachment</th>
                                                                <th style="width:58%">Attachment Purpose</th>
                                                                <th style="width:2%"><button type="button" name="addplus" onclick="add_attachment();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="attachments_table">
                                                            <tr>
                                                                <td>1</td>
                                                                <td>
                                                                    <input type="file" name="monitorattachment[]" id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="modal-footer">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <input type="hidden" name="store_checklists" id="store_checklists" value="store_checklists">
                                            <input type="hidden" name="projid" id="issue_projid">
                                            <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                        </div>
                                    </div> <!-- /modal-footer -->
                                </form>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <div id="previous_issues">
                                    <h4 class="text-danger">No records found!!</h4>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /modal-footer -->

                </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
        </div>
        <!-- end issues modal  -->

        <div class="modal fade" id="handOverItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info"> Project</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item list-group-item-action active">Project : <span id="project"></span> </li>
                            </ul>
                        </div>
                        <form class="form-horizontal" id="add_handover_details" action="" method="POST">
                            <?= csrf_token_html(); ?>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-comment" aria-hidden="true"></i> Handover Remark(s)
                                </legend>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="control-label">Remarks *:</label>
                                    <div class="form-line">
                                        <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i> Means of Verification (Files/Documents)
                                </legend>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width:2%">#</th>
                                                        <th style="width:40%">Attachments</th>
                                                        <th style="width:58%">Attachment Purpose</th>
                                                        <th style="width:2%"><button type="button" name="addplus" onclick="add_attachment();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="attachments_table1">
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            <input type="file" name="handoverattachment[]" id="handoverattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="modal-footer">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <input type="hidden" name="store_handover" id="store_handover" value="store_handover">
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <input type="hidden" name="workflow_stage" id="workflow_stage" value="<?= $workflow_stage ?>">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" value="button" id="tag-form-submit1"> Save</button>
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div> <!-- /modal-footer -->
                        </form>
                    </div> <!-- /modal-footer -->

                </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
        </div>
        <!-- End add item -->
<?php

    } catch (PDOException $ex) {
        customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
    }
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script>
    const redirect_url = "add-project-handover.php";
</script>
<script src="assets/js/monitoring/issues.js"></script>
<script src="assets/js/projects/view-project.js"></script>
<script src="assets/js/handover/index.js"></script>