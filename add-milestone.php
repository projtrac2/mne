<?php
try {

    require('includes/head.php');
    if ($permission) {
        $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : "";
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid AND projstage=:projstage");
        $query_rsProjects->execute(array(":projid" => $projid, ":projstage" => $workflow_stage));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        if ($totalRows_rsProjects > 0) {
            $progid = $row_rsProjects['progid'];
            $project = $row_rsProjects['projname'];
            $sectorid = $row_rsProjects['projsector'];
            $project_sub_stage = $row_rsProjects['proj_substage'];
            $workflow_stage = $row_rsProjects['projstage'];
            $project_directorate = $row_rsProjects['directorate'];


            $approval_stage = ($project_sub_stage  >= 2) ? true : false;
            $approve_details = "{
                get_edit_details: 'details',
                projid:$projid,
                workflow_stage:$workflow_stage,
                project_directorate:$project_directorate,
                project_name:'$project',
                sub_stage:'$project_sub_stage',
            }";

            function validate_milestones()
            {
                global $db, $projid;
                $query_rsMilestone = $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid=:projid ");
                $query_rsMilestone->execute(array(":projid" => $projid));
                $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                $condition  = [];
                if ($totalRows_rsMilestone > 0) {
                    while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                        $milestone_id = $row_rsMilestone['id'];
                        $milestone_type = $row_rsMilestone['milestone_type'];
                        if ($milestone_type == 1) {
                            $query_rsOutput = $db->prepare("SELECT * FROM tbl_project_milestone_outputs WHERE milestone_id=:milestone_id");
                            $query_rsOutput->execute(array(":milestone_id" => $milestone_id));
                            $totalRows_rsOutput = $query_rsOutput->rowCount();
                            if ($totalRows_rsOutput > 0) {
                                while ($row_rsOutput = $query_rsOutput->fetch()) {
                                    $output_id = $row_rsOutput['output_id'];
                                    $query_rsMl =  $db->prepare("SELECT * FROM tbl_milestone_output_subtasks WHERE output_id =:output_id AND milestone_id=:milestone_id");
                                    $query_rsMl->execute(array(":output_id" => $output_id, ":milestone_id" => $milestone_id));
                                    $Rows_rsMl = $query_rsMl->rowCount();
                                    $condition[] = $Rows_rsMl > 0 ? true : false;
                                }
                            } else {
                                $condition[] = false;
                            }
                        } else {
                            $query_Output =  $db->prepare("SELECT total_target, id FROM tbl_project_details WHERE projid =:projid");
                            $query_Output->execute(array(":projid" => $projid));
                            $total_Output = $query_Output->rowCount();

                            if ($total_Output > 0) {
                                while ($row_rsOutput = $query_Output->fetch()) {
                                    $output_target = $row_rsOutput['total_target'];
                                    $output_id = $row_rsOutput['id'];
                                    $query_rsMl =  $db->prepare("SELECT SUM(target) as target FROM tbl_project_milestone_outputs WHERE output_id =:output_id");
                                    $query_rsMl->execute(array(":output_id" => $output_id));
                                    $Rows_rsMl = $query_rsMl->fetch();
                                    $target_used = !is_null($Rows_rsMl['target'])  ? $Rows_rsMl['target'] : 0;
                                    $condition[] = $output_target == $target_used ? true : false;
                                }
                            }
                        }
                    }
                }


                $query_Output =  $db->prepare("SELECT total_target, id FROM tbl_project_details WHERE projid =:projid");
                $query_Output->execute(array(":projid" => $projid));
                $total_Output = $query_Output->rowCount();

                if ($total_Output > 0) {
                    while ($row_rsOutput = $query_Output->fetch()) {
                        $output_target = $row_rsOutput['total_target'];
                        $output_id = $row_rsOutput['id'];
                        $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id");
                        $query_rsTasks->execute(array(":output_id" => $output_id));
                        $totalRows_rsTasks = $query_rsTasks->rowCount();

                        if ($totalRows_rsTasks > 0) {
                            while ($row = $query_rsTasks->fetch()) {
                                $subtask_id = $row['tkid'];
                                $query_rsMl =  $db->prepare("SELECT * FROM tbl_milestone_output_subtasks WHERE subtask_id =:subtask_id");
                                $query_rsMl->execute(array(":subtask_id" => $subtask_id));
                                $Rows_rsMl = $query_rsMl->rowCount();
                                $condition[] = $Rows_rsMl > 0 ? true : false;
                            }
                        }
                    }
                }


                return  !in_array(false, $condition) ? true : false;
            }

            $approve = $project_sub_stage > 1 ? true : false;

?>
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon ?>
                            <?php echo $pageTitle ?>
                            <div class="btn-group" style="float:right">
                                <a type="button" data-toggle="modal" onclick="add_details({}, 1)" data-target="#outputItemModal" id="outputItemModalBtnrow" class="btn btn-success " style="margin-right: 10px;">
                                    Add Milestone
                                </a>
                                <a type="button" id="outputItemModalBtnrow" href="add-project-milestones.php" class="btn btn-warning pull-right" style="margin-right:10px;">
                                    Go Back
                                </a>
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
                                                <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $project ?> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php
                                            $query_rsMilestone = $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid=:projid");
                                            $query_rsMilestone->execute(array(":projid" => $projid));
                                            $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                            if ($totalRows_rsMilestone > 0) {
                                                $counter = "";
                                                while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                                    $counter++;
                                                    $milestone_name = $row_rsMilestone['milestone'];
                                                    $milestone_id = $row_rsMilestone['id'];
                                                    $milestone_type = $row_rsMilestone['milestone_type'];
                                                    $options = "{
                                                milestone:'$milestone_name',
                                                milestone_id:'$milestone_id',
                                                milestone_type:'$milestone_type',
                                            }";

                                                    $query_rsOutput = $db->prepare("SELECT i.indicator_name, d.id,m.target FROM tbl_project_milestone_outputs m INNER JOIN tbl_project_details d ON m.output_id = d.id INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE milestone_id=:milestone_id");
                                                    $query_rsOutput->execute(array(":milestone_id" => $milestone_id));
                                                    $totalRows_rsOutput = $query_rsOutput->rowCount();
                                            ?>
                                                    <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Milestone <?= $counter ?>: <?= strtoupper($milestone_name) ?></legend>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <?php
                                                            if ($totalRows_rsOutput == 0) {
                                                            ?>
                                                                <a type="button" onclick="delete_milestone(<?= $options ?>)" id="outputItemModalBtnrow" class="btn btn-danger pull-right" style="margin-right:10px;">
                                                                    Delete Milestone
                                                                </a>
                                                            <?php
                                                            }
                                                            ?>
                                                            <a type="button" data-toggle="modal" onclick="add_details(<?= $options ?>, 2)" data-target="#outputItemModal" id="outputItemModalBtnrow" class="btn btn-primary pull-right" style="margin-right:10px;">
                                                                Edit Milestone
                                                            </a>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:5%" align="center">#</th>
                                                                            <th style="width:65%">Output</th>
                                                                            <?php
                                                                            if ($milestone_type != 1) {
                                                                            ?>
                                                                                <th style="width:20%">Target</th>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <th style="width:10%">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($totalRows_rsOutput > 0) {
                                                                            $mcounter = 0;
                                                                            while ($row_rsOutput = $query_rsOutput->fetch()) {
                                                                                $mcounter++;
                                                                                $output_id = $row_rsOutput['id'];
                                                                                $output_hashed = base64_encode("projid54321{$output_id}");
                                                                                $hashed_milestone_id = base64_encode("projid54321{$milestone_id}");
                                                                                $output_name = $row_rsOutput['indicator_name'];
                                                                                $target = $row_rsOutput['target'];
                                                                                $options = "{
                                                                            milestone:'$milestone_name',
                                                                            milestone_id:'$milestone_id',
                                                                            output:'$output_name',
                                                                            output_id:'$output_id',
                                                                            target:'$target',
                                                                        }";

                                                                                $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks WHERE output_id=:output_id AND  milestone_id=:milestone_id ");
                                                                                $query_rsChecked->execute(array(":output_id" => $output_id, ":milestone_id" => $milestone_id));
                                                                                $totalRows_rsChecked = $query_rsChecked->rowCount();
                                                                                $edit = $totalRows_rsChecked > 0 ? true : false;
                                                                        ?>
                                                                                <tr style="background-color:#FFFFFF">
                                                                                    <td align="center"><?= $counter . "." . $mcounter ?></td>
                                                                                    <td><?= $output_name ?></td>
                                                                                    <?php
                                                                                    if ($milestone_type != 1) {
                                                                                    ?>
                                                                                        <td><?= $target  ?></td>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                    <td>
                                                                                        <div class="btn-group">
                                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                Options <span class="caret"></span>
                                                                                            </button>
                                                                                            <ul class="dropdown-menu">
                                                                                                <?php
                                                                                                if ($approve) {
                                                                                                ?>
                                                                                                    <li>
                                                                                                        <a type="button" href="add-milestone-output-activities.php?output_id=<?= $output_hashed ?>&milestone_id=<?= $hashed_milestone_id ?>">
                                                                                                            <i class=" fa fa-pencil-square"></i> Check Activities
                                                                                                        </a>
                                                                                                    </li>
                                                                                                    <?php
                                                                                                } else {
                                                                                                    if ($milestone_type == 1) {
                                                                                                    ?>
                                                                                                        <li>
                                                                                                            <a type="button" href="add-milestone-output-activities.php?output_id=<?= $output_hashed ?>&milestone_id=<?= $hashed_milestone_id ?>">
                                                                                                                <i class=" fa fa-pencil-square"></i> <?= $edit ? "Edit" : "Add" ?> Activities
                                                                                                            </a>
                                                                                                        </li>
                                                                                                    <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                    <li>
                                                                                                        <a type="button" onclick="delete_output(<?= $options ?>)">
                                                                                                            <i class="fa fa-trash"></i> Delete Output
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
                                                    </fieldset>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>

                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <?php
                                            $cost_val = validate_milestones();
                                            if ($cost_val) {
                                                $assigned_responsible = check_if_assigned($projid, $workflow_stage, $project_sub_stage, 1);
                                                $approve_details = "{
                                                get_edit_details: 'details',
                                                projid:$projid,
                                                workflow_stage:$workflow_stage,
                                                project_directorate:$project_directorate,
                                                project_name:'$project',
                                                sub_stage:'$project_sub_stage',
                                            }";
                                                if ($assigned_responsible) {
                                                    if ($approval_stage) {
                                            ?>
                                                        <button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Approve</button>
                                                    <?php
                                                    } else {
                                                        $data_entry_details = "{
                                                        get_edit_details: 'details',
                                                        projid:$projid,
                                                        workflow_stage:$workflow_stage,
                                                        project_directorate:$project_directorate,
                                                        project_name:'$project',
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
            </section>

            <!-- Start Item more -->
            <div class="modal fade" tabindex="-1" role="dialog" id="outputItemModal">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> Project Milestone</h4>
                        </div>
                        <form class="form-horizontal" id="output_form" action="" method="POST">
                            <div class="modal-body">
                                <fieldset class="scheduler-border" id="milestone_div">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Milestone</legend>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <label class="control-label"> Milestone :</label>
                                        <div class="form-line">
                                            <input type="text" name="milestone_name" id="milestone_name" value="" placeholder="Please enter milestone name" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required="required">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="control-label"> Milestone Type *:</label>
                                        <div class="form-line">
                                            <select name="milestone_type" id="milestone_type" onchange="hide_milestone_type()" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" false required>
                                                <option value="">Select Milestone Type</option>
                                                <option value="1">Task Based</option>
                                                <option value="2">Output Based</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="milestone_data_task_based" class="row clearfix" style="margin-top: 20px;">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="milestone_table" style="width:100%">
                                                    <thead id="milestone_table_head">
                                                        <tr>
                                                            <th style="width:5%">#</th>
                                                            <th style="width:70%">Output</th>
                                                            <th style="width:5%">
                                                                <button type="button" name="addplus" id="addplus_output" onclick="add_task_output()" class="btn btn-success btn-sm addplus_output">
                                                                    <span class="glyphicon glyphicon-plus">
                                                                    </span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="milestone_task_table_body">
                                                        <tr></tr>
                                                        <tr id="hideinfo2" align="center">
                                                            <td colspan="3">Add Outputs!!</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="milestone_data_output_based" class="row clearfix" style="margin-top: 20px;">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="milestone_table" style="width:100%">
                                                    <thead id="milestone_table_head">
                                                        <tr>
                                                            <th style="width:5%">#</th>
                                                            <th style="width:70%">Output</th>
                                                            <th style="width:20%">Target</th>
                                                            <th style="width:5%">
                                                                <button type="button" name="addplus" id="addplus_output" onclick="add_row_output()" class="btn btn-success btn-sm addplus_output">
                                                                    <span class="glyphicon glyphicon-plus">
                                                                    </span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="milestone_table_body">
                                                        <tr></tr>
                                                        <tr id="hideinfo1" align="center">
                                                            <td colspan="5">Add Outputs!!</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <input type="hidden" name="store_output_data" id="store_output_data" value="new">
                                    <input type="hidden" name="milestone_id" id="milestone_id" value="">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="m-tag-form-submit" value="Save" />
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
            <!-- End Item more -->

            <!-- Start Item more -->
            <div class="modal fade" tabindex="-1" role="dialog" id="outputMilestoneItemModal">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> Project Milestone Output</h4>
                        </div>
                        <form class="form-horizontal" id="store_output_data_mile" action="" method="POST">
                            <div class="modal-body">
                                <fieldset class="scheduler-border" id="milestone_div">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Milestone Output</legend>
                                    <div id="milestone_data_task_based_1" class="row clearfix" style="margin-top: 20px;">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="milestone_table" style="width:100%">
                                                    <thead id="milestone_table_head">
                                                        <tr>
                                                            <th style="width:5%">#</th>
                                                            <th style="width:70%">Output</th>
                                                            <th style="width:5%">
                                                                <button type="button" name="addplus" id="addplus_output" onclick="add_task_output_1()" class="btn btn-success btn-sm addplus_output">
                                                                    <span class="glyphicon glyphicon-plus">
                                                                    </span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="milestone_task_table_body_1">
                                                        <tr></tr>
                                                        <tr id="hideinfo4" align="center">
                                                            <td colspan="3">Add Outputs!!</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="design" style="margin-bottom: 20px;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label class="control-label">Output *:</label>
                                            <div class="form-line">
                                                <select name="output" id="outputrow9999" onchange=' get_targets("row9999")' class="form-control validoutcome select_output" required="required">
                                                    <option value="">Select Output from list</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label class="control-label">Target *:</label>
                                            <div class="form-line">
                                                <input type="text" name="target" id="targetrow9999" value="" placeholder="Please enter target" onchange="calculate_maximum('row9999')" onkeyup="calculate_maximum('row9999')" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required="required">
                                                <input type="hidden" value="" id="target_valrow9999" />
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <input type="hidden" name="store_output_data_mile_d" id="store_output_data_mile_d" value="new">
                                    <input type="hidden" name="milestone_id" id="o_milestone_id" value="">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
            <!-- End Item more -->
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
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

require('includes/footer.php');
?>

<script>
    const redirect_url = "add-project-milestones.php";
</script>
<script src="assets/js/activities/output.js"></script>
<script src="assets/js/master/index.js"></script>