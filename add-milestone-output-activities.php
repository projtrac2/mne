<?php
require('includes/head.php');
if ($permission) {
    try {
        $decode_output_id = (isset($_GET['output_id']) && !empty($_GET["output_id"])) ? base64_decode($_GET['output_id']) : "";
        $output_id_array = explode("projid54321", $decode_output_id);
        $output_id = $output_id_array[1];

        $decode_milestone_id = (isset($_GET['milestone_id']) && !empty($_GET["milestone_id"])) ? base64_decode($_GET['milestone_id']) : "";
        $milestone_id_array = explode("projid54321", $decode_milestone_id);
        $project_milestone_id = $milestone_id_array[1];

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
        $output_duration = $total_Output > 0 ? $row_rsOutput['duration'] : "";
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

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid ");
        $query_Output->execute(array(":projid" => $projid));
        $row_rsOutput = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();

        function check_checked($milestone_id)
        {
            global $db, $project_milestone_id;
            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone ORDER BY parenttask");
            $query_rsTasks->execute(array(":milestone" => $milestone_id));
            $totalRows_rsTasks = $query_rsTasks->rowCount();
            $checked = [];
            if ($totalRows_rsTasks > 0) {
                while ($row_rsTasks = $query_rsTasks->fetch()) {
                    $task_id = $row_rsTasks['tkid'];
                    $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks WHERE subtask_id=:sub_task AND  milestone_id=:milestone_id ");
                    $query_rsChecked->execute(array(":sub_task" => $task_id, ":milestone_id" => $project_milestone_id));
                    $totalRows_rsChecked = $query_rsChecked->rowCount();
                    $checked[] = $totalRows_rsChecked > 0 ? true : false;
                }
            }
            return in_array(true, $checked) ? true : false;
        }

        if ((isset($_POST["store_data"])) && ($_POST["store_data"] == "store_data")) {
            $projid = $_POST['projid'];
            $output_id =  $_POST['output_id'];
            $milestone_id = $_POST['milestone_id'];
            $tasks = isset($_POST['task']) > 0 ? count($_POST['task']) : 0;

            $sql = $db->prepare("DELETE FROM `tbl_milestone_output_subtasks` WHERE milestone_id=:milestone_id AND output_id=:output_id");
            $results = $sql->execute(array(':milestone_id' => $milestone_id, ":output_id" => $output_id));

            if ($tasks > 0) {
                for ($i = 0; $i < $tasks; $i++) {
                    $task_id = $_POST['task'][$i];
                    $sub_tasks = isset($_POST['sub_task' . $task_id]) ? count($_POST['sub_task' . $task_id]) : 0;
                    if ($sub_tasks > 0) {
                        for ($j = 0; $j < $sub_tasks; $j++) {
                            $subtask_id = $_POST['sub_task' . $task_id][$j];
                            $sql = $db->prepare("INSERT INTO tbl_milestone_output_subtasks (projid,output_id,milestone_id,task_id,subtask_id) VALUES (:projid,:output_id,:milestone_id,:task_id,:subtask_id)");
                            $results = $sql->execute(array(':projid' => $projid, ":output_id" => $output_id, ':milestone_id' => $milestone_id,  ":task_id" => $task_id, ':subtask_id' => $subtask_id));
                        }
                    }
                }
            }

            $projid_hashed = base64_encode("projid54321{$projid}");
            $redirect_url = "add-milestone.php?projid=" . $projid_hashed;
            $msg = 'Data added Successfully';
            $results = "<script type=\"text/javascript\">
                swal({
                    title: \"Success!\",
                    text: \" $msg\",
                    type: 'Success',
                    timer: 2000,
                    'icon':'success',
                showConfirmButton: false });
                setTimeout(function(){
                    window.location.href = '$redirect_url';
                }, 2000);
            </script>";
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
                        <a type="button" id="outputItemModalBtnrow" href="add-milestone.php?projid=<?= base64_encode("projid54321{$projid}") ?>" class="btn btn-warning pull-right" style="margin-right:10px;">
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
                                        <li class="list-group-item"><strong><?= $mapping_type == 1 ? "Site/s" : "Location/s" ?>: </strong> <?= $sites ?> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <form class="form-horizontal" id="activities_form" action="" onsubmit="return validateForm()" method="POST">
                                <?= csrf_token_html(); ?>
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
                                                $milestone_sequence = $row_rsMilestone['parent'];

                                                $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone ORDER BY parenttask");
                                                $query_rsTasks->execute(array(":milestone" => $milestone_id));
                                                $totalRows_rsTasks = $query_rsTasks->rowCount();


                                                $checked = check_checked($milestone_id);
                                        ?>
                                                <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Task <?= $counter ?>: <?= strtoupper($milestone_name) ?></legend>
                                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                        <div class="form-line">
                                                            <input name="projevaluation" onchange="check_box(<?= $milestone_id ?>)" type="checkbox" <?= $checked == 1 ? "checked" : "" ?> id="all<?= $milestone_id ?>" class="with-gap radio-col-green sub_task" />
                                                            <label for="all<?= $milestone_id ?>"><span id="checked<?= $milestone_id ?>"> <?= $checked ? "Uncheck" : "Check" ?> All</span></label>
                                                            <input type="hidden" name="task[]" value="<?= $milestone_id ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                <thead>
                                                                    <tr style="background-color:#0b548f; color:#FFF">
                                                                        <td style="width:5%"></td>
                                                                        <th style="width:5%" align="center">#</th>
                                                                        <th style="width:40%">Sub-Task</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if ($totalRows_rsTasks > 0) {
                                                                        $mcounter = 0;
                                                                        while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                            $mcounter++;
                                                                            $task_name = $row_rsTasks['task'];
                                                                            $task_id = $row_rsTasks['tkid'];

                                                                            $query_rsChecked = $db->prepare("SELECT * FROM tbl_milestone_output_subtasks WHERE subtask_id=:sub_task AND  milestone_id=:milestone_id ");
                                                                            $query_rsChecked->execute(array(":sub_task" => $task_id, ":milestone_id" => $project_milestone_id));
                                                                            $totalRows_rsChecked = $query_rsChecked->rowCount();
                                                                            $checked = $totalRows_rsChecked > 0 ? true : false;
                                                                    ?>
                                                                            <tr style="background-color:#FFFFFF">
                                                                                <td align="center">
                                                                                    <div class="form-line">
                                                                                        <input name="sub_task<?= $milestone_id ?>[]" value="<?= $task_id ?>" <?= ($project_sub_stage > 1) ? ' readonly ' : '' ?> onchange="check_item(<?= $milestone_id ?>)" type="checkbox" <?= $checked == 1 ? "checked" : "" ?> id="evaluation<?= $milestone_id . $mcounter . $counter ?>" class="with-gap radio-col-green tasks sub_task<?= $milestone_id ?>" />
                                                                                        <label for="evaluation<?= $milestone_id . $mcounter . $counter ?>"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td align="center"><?php echo $counter . "." . $mcounter ?></td>
                                                                                <td><?= $task_name ?></td>
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
                                            } while ($row_rsMilestone = $query_rsMilestone->fetch());
                                        }
                                        ?>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        <input type="hidden" name="store_data" id="store_data" value="store_data">
                                        <input type="hidden" name="output_id" id="output_id" value="<?= $output_id ?>">
                                        <input type="hidden" name="milestone_id" id="milestone_id" value="<?= $project_milestone_id ?>">
                                        <input type="hidden" name="projid" id="projid" class="projid" value="<?= $projid ?>">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>

<script>
    const ajax_url = "ajax/activities/index";
    const output_duration = '<?= $output_duration ?>';
</script>


<script>
    function check_box(task_id) {
        var checked = $(`.sub_task${task_id}`).is(':checked');
        if (checked) {
            $(`#checked${task_id}`).html("Check");
            $(`.sub_task${task_id}`).prop("checked", false);
        } else {
            $(`.sub_task${task_id}`).prop("checked", true);
            $(`#checked${task_id}`).html("Uncheck");
            console.log("Checking")
        }
    }

    function check_item(task_id) {
        var checked = $(`.sub_task${task_id}`).is(':checked');
        if (!checked) {
            $(`#checked${task_id}`).html("Check");
            $(`#all${task_id}`).prop("checked", false);
        } else {
            $(`#all${task_id}`).prop("checked", true);
            $(`#checked${task_id}`).html("Uncheck");
        }
    }

    function validateForm() {
        var checked = $(`.tasks`).is(':checked');

        if (!checked) {
            error_alert("Ensure you have attached atleast one sub-task");
            return false;
        }
        return checked;
    }
</script>
<script src="assets/js/activities/index.js"></script>