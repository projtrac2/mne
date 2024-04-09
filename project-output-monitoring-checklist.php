<?php
try {

require('includes/head.php');
if ($permission) {
        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage AND (p.projstatus=3 OR p.projstatus=4 OR p.projstatus=11) AND proj_substage = 0 ORDER BY p.projid DESC");
        $query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $team_type = 4;

        $query_risk_impact =  $db->prepare("SELECT * FROM tbl_risk_impact WHERE active = 1");
        $query_risk_impact->execute();

        $query_risk_categories = $db->prepare("SELECT * FROM tbl_projrisk_categories");
        $query_risk_categories->execute();

        function check_observation_responsible($projid, $workflow_stage, $team_type, $role)
        {
            global $db,  $user_name, $workflow_stage, $user_designation;
            $output_responsible = $standin_responsible = false;
            if ($user_designation == 1) {
                $output_responsible = true;
            } else {
                $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible AND role=:role");
                $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => $team_type, ":responsible" => $user_name, ":role" => $role));
                $total_rsOutput = $query_rsOutput->rowCount();
                $output_responsible = $total_rsOutput > 0 ? true : false;

                $query_rsOutput_standin = $db->prepare("SELECT * FROM tbl_project_team_leave  WHERE projid =:projid AND assignee=:user_name AND status = 1 AND team_type =:team_type");
                $query_rsOutput_standin->execute(array(":projid" => $projid, ":user_name" => $user_name, ":team_type" => $team_type));
                $row_rsOutput_standin = $query_rsOutput_standin->fetch();
                $total_rsOutput_standin = $query_rsOutput_standin->rowCount();

                if ($total_rsOutput_standin > 0) {
                    $owner_id = $row_rsOutput_standin['owner'];
                    $query_rsOutput = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND stage=:workflow_stage AND team_type =:team_type AND responsible=:responsible AND role=:role");
                    $query_rsOutput->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage, ":team_type" => $team_type, ":responsible" => $owner_id, ":role" => $role));
                    $total_rsOutput = $query_rsOutput->rowCount();
                    $standin_responsible = $total_rsOutput > 0 ? true : false;
                }
            }
            return $output_responsible || $standin_responsible ? true : false;
        }

        function check_if_completion($projid)
        {
            global $db;
            $query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid = :projid AND complete=0");
            $query_rsPlan->execute(array(":projid" => $projid));
            $totalRows_plan = $query_rsPlan->rowCount();

            return $totalRows_plan > 0 ? true : false;
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
                                        <tr id="colrow">
                                            <th style="width:5%" align="center">#</th>
                                            <th style="width:10%">Code</th>
                                            <th style="width:31%">Project </th>
                                            <th style="width:12%">Start Date</th>
                                            <th style="width:12%">End date</th>
                                            <th style="width:10%">Progress</th>
                                            <th style="width:10%">Status</th>
                                            <th style="width:10%">Action</th>
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
                                                $progress = number_format(calculate_project_progress($projid, $implementation), 2);
                                                $projstatus = $row_rsProjects['projstatus'];
                                                $projectid = base64_encode("projid54321{$projid}");

                                                $start_date = date('Y-m-d');
                                                $projduration =  $row_rsProjects['projduration'];
                                                $project_start_date =  $row_rsProjects['projstartdate'];
                                                $project_end_date = date('Y-m-d', strtotime($project_start_date . ' + ' . $projduration . ' days'));
                                                $projcontractor =  $row_rsProjects['projcategory'];

                                                $monitoring_responsible = check_monitoring_responsible($projid, $workflow_stage, $team_type);
                                                $team_leader_responsible = check_observation_responsible($projid, $workflow_stage, $team_type, 2);

                                                if ($monitoring_responsible) {
                                                    $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid LIMIT 1");
                                                    $query_rsTask_Start_Dates->execute(array(':projid' => $projid));
                                                    $rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                    $total_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

                                                    if (!is_null($rows_rsTask_Start_Dates['start_date'])) {
                                                        $project_start_date =  $rows_rsTask_Start_Dates['start_date'];
                                                        $project_end_date =  $rows_rsTask_Start_Dates['end_date'];
                                                    } else {
                                                        if ($projcontractor == 2) {
                                                            $query_rsTender_start_Date = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
                                                            $query_rsTender_start_Date->execute(array(':projid' => $projid));
                                                            $rows_rsTender_start_Date = $query_rsTender_start_Date->fetch();
                                                            $total_rsTender_start_Date = $query_rsTender_start_Date->rowCount();
                                                            if ($total_rsTender_start_Date > 0) {
                                                                $project_start_date =  $rows_rsTender_start_Date['startdate'];
                                                                $project_end_date =  $rows_rsTender_start_Date['enddate'];
                                                            }
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
                                                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                                            ' . $progress . '%
                                                        </div>
                                                    </div>';
                                                    if ($progress == 100) {
                                                        $project_progress = '
                                                        <div class="progress" style="height:20px; font-size:10px; color:black">
                                                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                                            ' . $progress . '%
                                                            </div>
                                                        </div>';
                                                    }

                                                    $counter++;
                                        ?>
                                                    <tr>
                                                        <td align="center"><?= $counter ?></td>
                                                        <td><?= $projcode ?></td>
                                                        <td>
                                                            <div class="links" style="background-color:#9E9E9E; color:white; padding:5px;">
                                                                <a href="myprojectdash.php?proj=<?php echo $projectid; ?>" style="color:#FFF; font-weight:bold"><?= $projname ?></a>
                                                            </div>
                                                        </td>
                                                        <td><?= $project_start_date ?></td>
                                                        <td><?= $project_end_date ?></td>
                                                        <td><?= $project_progress ?></td>
                                                        <td><?= $status ?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <?php
                                                                    if (check_if_completion($projid)) {
                                                                    ?>
                                                                        <li>
                                                                            <a type="button" href="project-monitoring?projid=<?= $projid_hashed ?>">
                                                                                <i class="fa fa-list-ol text-primary"></i> Monitor
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                    }
                                                                    if ($team_leader_responsible) {
                                                                        if (check_if_completion($projid)) {
                                                                        ?>
                                                                            <li>
                                                                                <a type="button" href="project-monitoring-observations?projid=<?= $projid_hashed ?>">
                                                                                    <i class="fa fa-commenting text-warning"></i> Observations
                                                                                </a>
                                                                            </li>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="add_project_issues(<?= $projid ?>, '<?= htmlspecialchars($projname) ?>')">
                                                                                <i class="fa fa-exclamation-triangle text-danger"></i> Issues
                                                                            </a>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <li>
                                                                        <a type="button" href="project-collaboration.php?projid=<?= $projid_hashed ?>">
                                                                            <i class="fa fa-comments-o" style="color:green"></i> Collaboration
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
                                                <select name="issue_area" id="issue_area" class="form-control topic" onchange="adjustscope(<?= $projid ?>)" data-live-search="true" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
                                                    <option value="" selected="selected" class="selection">... Select Issue Area...</option>
                                                    <?php
                                                    $query_issue_area =  $db->prepare("SELECT * FROM tbl_issue_areas WHERE type=1 AND active = 1");
                                                    $query_issue_area->execute();
                                                    while ($row_issue_area = $query_issue_area->fetch()) {
                                                    ?>
                                                        <font color="black">
                                                            <option value="<?php echo $row_issue_area['id'] ?>" class="selection"><?php echo $row_issue_area['issue_area'] ?></option>
                                                        </font>
                                                    <?php
                                                    }
													?>
                                                </select>
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
                                        <input type="hidden" name="projid" id="projid">
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

    <script src="assets/js/monitoring/issues.js"></script>
    <!-- end issues modal  -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');

} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}
?>