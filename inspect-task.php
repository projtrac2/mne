<?php
require('includes/head.php'); 
if ($permission) {
    try {
        $taskid = isset($_GET['taskid']) ? base64_decode($_GET['taskid']) : "";

        $projid = $project_name = $task_name = "";
        $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE tkid=:task_id ORDER BY parenttask");
        $query_rsTasks->execute(array(":task_id" => $task_id));
        $row_rsTasks = $query_rsTasks->fetch();
        $totalRows_rsTasks = $query_rsTasks->rowCount();
        if ($totalRows_rsTasks > 0) {
            $projid = $row_rsTasks['projid'];
            $task_name = $row_rsTasks['task'];
            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid");
            $query_rsProjects->execute(array(":projid" => $projid));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();
            $progid = $project = $sectorid = "";
            $project_name = ($totalRows_rsProjects > 0) ? $row_rsProjects['projname'] : "";
        }
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
                        <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-primary pull-right">
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
                                        <li class="list-group-item"><strong>Task: </strong> <?= $task_name ?> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Specifications</legend>


                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr style="background-color:#0b548f; color:#FFF">
                                                            <th style="width:5%" align="center">#</th>
                                                            <th style="width:75%">Items</th>
                                                            <th style="width:20%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_task_parameters WHERE task_id=:task_id");
                                                        $query_rsTask_parameters->execute(array(":task_id" => $taskid));
                                                        $row_rsTask_parameters = $query_rsTask_parameters->fetch();
                                                        $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();

                                                        if ($totalRows_rsTask_parameters > 0) {
                                                            $tcounter = 0;
                                                            do {
                                                                $tcounter++;
                                                                $task_parameter_name = $row_rsTask_parameters['parameter'];
                                                                $unit_of_measure = $row_rsTask_parameters['unit_of_measure'];
                                                                $task_parameter_id = $row_rsTask_parameters['id'];

                                                                $query_rsSpecifions = $db->prepare("SELECT * FROM tbl_project_specifications WHERE parameter_id=:parameter_id");
                                                                $query_rsSpecifions->execute(array(":parameter_id" => $task_parameter_id));
                                                                $row_rsSpecifions = $query_rsSpecifions->fetch();
                                                                $totalRows_rsSpecifions = $query_rsSpecifions->rowCount();

                                                                if ($totalRows_rsSpecifions > 0) {
                                                                    $spec_counter = 0;
                                                                    do {
                                                                        $spec_counter++;
                                                                        $specification = $row_rsSpecifions['specification'];
                                                                        $specification_id = $row_rsSpecifions['id'];
                                                        ?>
                                                                        <tr style="background-color:#FFFFFF">
                                                                            <td align="center"><?php echo $tcounter . '.' . $spec_counter  . ")" ?></td>
                                                                            <td><?= $specification ?></td>
                                                                            <td>
                                                                                <div class="form-line">
                                                                                    <select name="compliance" id="compliance" onchange="inspect_project()" class="form-control">
                                                                                        <option value="">Compliance</option>
                                                                                        <option value="1">Compliant</option>
                                                                                        <option value="2">Non-Compliant</option>
                                                                                    </select>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    } while ($row_rsSpecifions = $query_rsSpecifions->fetch());
                                                                    ?>
                                                                    <tr class="bg-grey">
                                                                        <td align="center"><?php echo $tcounter ?></td>
                                                                        <td><?= $task_parameter_name ?></td>
                                                                        <td>
                                                                            <div class="form-line">
                                                                                <select name="location" id="location" onchange="get_op_milestones()" class="form-control">
                                                                                    <option value="">Score</option>
                                                                                    <?php
                                                                                    for ($i = 0; $i <= 10; $i++) {
                                                                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                        <?php
                                                                }
                                                            } while ($row_rsTask_parameters = $query_rsTask_parameters->fetch());
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- add item -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Inspection</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="add_modal_form">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-comment" aria-hidden="true"></i> Remark(s)
                                            </legend>
                                            <div id="comment_section">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="control-label">Remark(s) :</label>
                                                    <br>
                                                    <div class="form-line">
                                                        <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Issue(s)
                                            </legend>
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:2%">#</th>
                                                                    <th style="width:21%">Issue</th>
                                                                    <th style="width:75%">Description</th>
                                                                    <th style="width:2%"><button type="button" name="addplus" onclick="add_issues();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="issues_table">
                                                                <tr></tr>
                                                                <tr id="removeTr" class="text-center">
                                                                    <td colspan="4">Add Issues</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Task Checklist Questions -->
                                        </fieldset>

                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-paperclip" aria-hidden="true"></i> Means of Verification (Files/Documents)
                                            </legend>
                                            <!-- Task Checklist Questions -->
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="card" style="margin-bottom:-20px">
                                                        <div class="body">
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
                                                </div>
                                            </div>
                                            <!-- Task Checklist Questions -->
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->
                        <div class="modal-footer">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <input type="hidden" name="store" id="store" value="new">
                                <button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value="">Save</button>
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

<script>
    function inspect_project() {
        var compliance = $("#compliance").val();
        if (compliance == "2") {
            console.log(compliance);
            $('.modal').modal('show');
        }
    }
</script>