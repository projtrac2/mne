    <?php
    require('includes/head.php');
    if ($permission) {
        try {
            $decode_output_id = (isset($_GET['output_id']) && !empty($_GET["output_id"])) ? base64_decode($_GET['output_id']) : "";
            $output_id_array = explode("projid54321", $decode_output_id);
            $output_id = $output_id_array[1];

            function get_sites($output_id, $mapping_type)
            {
                global $db;
                $site_name = [];
                if ($mapping_type == 1) {
                    $query_Output = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
                    $query_Output->execute(array(":output_id" => $output_id));
                    $total_Output = $query_Output->rowCount();
                    if ($total_Output > 0) {
                        while ($row_rsOutput = $query_Output->fetch()) {
                            $site_name[] = $row_rsOutput['site'];
                        }
                    }
                } else {
                    $query_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
                    $query_Output->execute(array(":output_id" => $output_id));
                    $total_Output = $query_Output->rowCount();
                    if ($total_Output > 0) {
                        while ($row_rsOutput = $query_Output->fetch()) {
                            $site_name[] = $row_rsOutput['state'];
                        }
                    }
                }
                return implode(",", $site_name);
            }

            $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE id = :output_id ");
            $query_Output->execute(array(":output_id" => $output_id));
            $row_rsOutput = $query_Output->fetch();
            $total_Output = $query_Output->rowCount();


            $projid = $total_Output > 0 ? $row_rsOutput['projid'] : "";

            $indicator_id = $total_Output > 0 ? $row_rsOutput['indicator'] : "";
            $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indicator_id");
            $query_rsIndicator->execute(array(":indicator_id" => $indicator_id));
            $row_rsIndicator = $query_rsIndicator->fetch();
            $totalRows_rsIndicator = $query_rsIndicator->rowCount();

            $output_name = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_name'] : "";
            $mapping_type = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_mapping_type'] : "";
            $projid = $total_Output > 0 ?  $row_rsOutput['projid'] : "";
            $sites = get_sites($output_id, $mapping_type);


            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid");
            $query_rsProjects->execute(array(":projid" => $projid));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();
            $progid = $project = $sectorid = "";
            $project_name = ($totalRows_rsProjects > 0) ? $row_rsProjects['projname'] : "";
            $project_sub_stage = ($totalRows_rsProjects > 0) ? $row_rsProjects['proj_substage'] : "";
            $approve = $project_sub_stage > 1 ? true : false;

            $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid ");
            $query_Output->execute(array(":projid" => $projid));
            $row_rsOutput = $query_Output->fetch();
            $total_Output = $query_Output->rowCount();



            // //get indicator
            $other_details = "
            output_details:{
                output_id: '$output_id',
                indicator_id: '$indicator_id',
                output_name: '$output_name',
                mapping_type:'$mapping_type',
            }";


            $milestone_id = $milestone_name = $milestone_location  = "";
            $task_id = $task_name =   "";
            $options = "{
            $other_details,
            milestone_details:{
                    milestone_id: '$milestone_id',
                    milestone_name: '$milestone_name',
                    milestone_location: '$milestone_location',
                },
                task_details:{
                task_id: '$task_id',
                task_name: '$task_name',
                },
            }";
        } catch (PDOException $ex) {
            $results = flashMessage("An error occurred: " . $ex->getMessage());
        }
    ?>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?>
                        <?php echo $pageTitle ?>
                        <div class="btn-group" style="float:right">
                            <a type="button" data-toggle="modal" onclick="add_details(<?= $options ?>, 1)" data-target="#outputItemModal" id="outputItemModalBtnrow" class="btn btn-warning " style="margin-right: 10px;">
                                Add Task
                            </a>
                            <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right" style="margin-right:10px;">
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
                                            <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $project_name ?> </li>
                                            <li class="list-group-item"><strong>Output: </strong> <?= $output_name ?> </li>
                                            <li class="list-group-item"><strong><?= $mapping_type == 1 ? "Site/s" : $level2label ?>: </strong> <?= $sites ?> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php
                                        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
                                        $query_rsMilestone->execute(array(":output_id" => $output_id));
                                        $row_rsMilestone = $query_rsMilestone->fetch();
                                        $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                        if ($totalRows_rsMilestone > 0) {
                                            $counter = "";
                                            do {
                                                $counter++;
                                                $milestone_name = $row_rsMilestone['milestone'];
                                                $milestone_id = $row_rsMilestone['msid'];
                                                $milestone_location = $row_rsMilestone['location'];

                                                $milestone_details = "milestone_details:{
                                                milestone_id: '$milestone_id',
                                                milestone_name: '$milestone_name',
                                                milestone_location: '$milestone_location',
                                            },";

                                                $task_id = $task_name =  "";
                                                $options = "{
                                                $other_details,
                                                $milestone_details
                                                task_details:{
                                                task_id: '$task_id',
                                                task_name: '$task_name',
                                                },
                                            }";

                                                $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone");
                                                $query_rsTasks->execute(array(":milestone" => $milestone_id));
                                                $row_rsTasks = $query_rsTasks->fetch();
                                                $totalRows_rsTasks = $query_rsTasks->rowCount();
                                        ?>
                                                <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Task <?= $counter ?>: <?= strtoupper($milestone_name) ?></legend>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <a type="button" data-toggle="modal" onclick="add_details(<?= $options ?>, 2)" data-target="#outputItemModal" id="outputItemModalBtnrow" class="btn btn-primary pull-right" style="margin-right:10px;">
                                                            Add Sub-Task
                                                        </a>
                                                        <?php
                                                        if ($totalRows_rsTasks == 0) {
                                                        ?>
                                                            <a type="button" onclick="destroy_task(<?= $milestone_id ?>, 1)" id="outputItemModalBtnrow" class="btn btn-danger pull-right" style="margin-right:10px;">
                                                                Delete Task
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>
                                                        <a type="button" data-toggle="modal" onclick="add_details(<?= $options ?>, 1, 1)" data-target="#outputItemModal" id="outputItemModalBtnrow" class="btn btn-warning pull-right" style="margin-right:10px;">
                                                            Edit Task
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                <thead>
                                                                    <tr style="background-color:#0b548f; color:#FFF">
                                                                        <th style="width:5%" align="center">#</th>
                                                                        <th style="width:50%">Sub-Task</th>
                                                                        <th style="width:40%">Unit</th>
                                                                        <th style="width:5%" data-orderable="false">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if ($totalRows_rsTasks > 0) {
                                                                        $mcounter = 0;
                                                                        do {
                                                                            $mcounter++;
                                                                            $task_name = $row_rsTasks['task'];
                                                                            $task_id = $row_rsTasks['tkid'];
                                                                            $unit_of_measure = $row_rsTasks['unit_of_measure'];
                                                                            $task_details = "
                                                                            task_details:{
                                                                                task_id: '$task_id',
                                                                                task_name: '$task_name',
                                                                                unit_of_measure: '$unit_of_measure',
                                                                            },";
                                                                            $options = "{
                                                                            $other_details,
                                                                            $milestone_details
                                                                            $task_details
                                                                        }";

                                                                            $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                            $query_rsIndUnit->execute(array(":unit_id" => $unit_of_measure));
                                                                            $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                            $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                            $measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                                    ?>
                                                                            <tr style="background-color:#FFFFFF">
                                                                                <td align="center"><?= $mcounter ?></td>
                                                                                <td><?= $task_name ?></td>
                                                                                <td><?= $measure ?></td>
                                                                                <td>
                                                                                    <div class="btn-group">
                                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                            Options <span class="caret"></span>
                                                                                        </button>
                                                                                        <ul class="dropdown-menu">
                                                                                            <li>
                                                                                                <a type="button" data-toggle="modal" data-target="#outputItemModal" id="addFormModalBtn" onclick="add_details(<?= $options ?>, 2,1)">
                                                                                                    <i class="fa fa-pencil-square"></i> Edit Sub-Task
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="destroy_task(<?= $task_id ?>, 2)">
                                                                                                    <i class="fa fa-trash-o"></i> Remove Sub-Task
                                                                                                </a>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                    <?php
                                                                        } while ($row_rsTasks = $query_rsTasks->fetch());
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                        <?php
                                            } while ($row_rsMilestone = $query_rsMilestone->fetch());
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
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> Project Output Activities</h4>
                    </div>
                    <form class="form-horizontal" id="add_items" action="" method="POST">
                        <div class="modal-body">
                            <fieldset class="scheduler-border" id="milestone_div">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Activities </legend>
                                <div id="design">

                                </div>
                                <div id="milestone_data">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="milestone_table" style="width:100%">
                                                <thead id="milestone_table_head">

                                                </thead>
                                                <tbody id="milestone_table_body">
                                                    <tr></tr>
                                                    <tr id="hideinfo1" align="center">
                                                        <td colspan="5">Add Tasks!!</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="scheduler-border" id="tasks_div">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Sub-task Details </legend>
                                <div class="col-md-12" id="projoutputTable">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="tasks_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="50%">Sub-task</th>
                                                    <th width="40%">Unit</th>
                                                    <th width="5%">
                                                        <button type="button" name="addplus" id="addplus_output" onclick="add_row_items(2);" class="btn btn-success btn-sm addplus_output">
                                                            <span class="glyphicon glyphicon-plus">
                                                            </span>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tasks_table_body">
                                                <tr></tr>
                                                <tr id="hideinfo2" align="center">
                                                    <td colspan="5">Add Subtasks!!</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="modal-footer">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <input type="hidden" name="store_data" id="store_data" value="designs">
                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                <input type="hidden" name="output_id" id="output_id" value="<?= $output_id ?>">
                                <input type="hidden" name="milestone_id" id="milestone_id" value="">
                                <input type="hidden" name="task_id" id="task_id" value="">
                                <input type="hidden" name="mapping_type" id="mapping_type" value="">
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

    require('includes/footer.php');
    ?>

    <script>
        const ajax_url = "ajax/activities/index";
    </script>

    <script src="assets/js/activities/index.js"></script>