<?php
try {
    //code...

$query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
$query_Sites->execute(array(":projid" => $projid));
$rows_sites = $query_Sites->rowCount();
if ($rows_sites > 0) {
    $counter = 0;
    while ($row_Sites = $query_Sites->fetch()) {
        $site_id = $row_Sites['site_id'];
        $site = $row_Sites['site'];
        $counter++;
?>
        <fieldset class="scheduler-border">
            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                SITE <?= $counter ?> : <?= $site . " " . $site_id ?>
            </legend>
            <?php
            $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
            $query_Site_Output->execute(array(":site_id" => $site_id));
            $rows_Site_Output = $query_Site_Output->rowCount();
            if ($rows_Site_Output > 0) {
                $output_counter = 0;
                while ($row_Site_Output = $query_Site_Output->fetch()) {
                    $output_counter++;
                    $output_id = $row_Site_Output['outputid'];
                    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                    $query_Output->execute(array(":outputid" => $output_id));
                    $row_Output = $query_Output->fetch();
                    $total_Output = $query_Output->rowCount();
                    if ($total_Output) {
                        $output_id = $row_Output['id'];
                        $output = $row_Output['indicator_name'];
            ?>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border" style="background-color:#f0f0f0; border-radius:3px">
                                OUTPUT <?= $output_counter ?> : <?= $output ?>
                            </legend>
                            <?php
                            $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
                            $query_rsMilestone->execute(array(":output_id" => $output_id));
                            $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                            if ($totalRows_rsMilestone > 0) {
                                $task_counter = 0;
                                while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                    $milestone = $row_rsMilestone['milestone'];
                                    $msid = $row_rsMilestone['msid'];
                                    $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id ");
                                    $query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id));
                                    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                    $edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;
                                    $details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'edit' => $edit);
                                    $task_counter++;
                            ?>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card-header">
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <h5><u>
                                                                TASK <?= $task_counter ?>: <?= $milestone ?>
                                                                <?php
                                                                if ($implementation_type == 1) {
                                                                ?>
                                                                    <div class="btn-group" style="float:right">
                                                                        <div class="btn-group" style="float:right">
                                                                            <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="get_tasks(<?= htmlspecialchars(json_encode($details)) ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                                <?php echo $totalRows_rsTask_Start_Dates > 0 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                }
                                                                ?>
                                                            </u>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:5%">#</th>
                                                            <th style="width:40%">Subtask</th>
                                                            <th style="width:15%">Unit of Measure</th>
                                                            <th style="width:10%">Duration</th>
                                                            <th style="width:15%">Start Date</th>
                                                            <th style="width:15%">End Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
                                                        $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
                                                        $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                        if ($totalRows_rsTasks > 0) {
                                                            $tcounter = 0;
                                                            while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                                $tcounter++;
                                                                $task_name = $row_rsTasks['task'];
                                                                $task_id = $row_rsTasks['tkid'];
                                                                $unit =  $row_rsTasks['unit_of_measure'];
                                                                $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                                $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id ");
                                                                $query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => $site_id, ":subtask_id" => $task_id));
                                                                $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                                $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                                $start_date = $end_date = $duration =  "";
                                                                if ($totalRows_rsTask_Start_Dates > 0) {
                                                                    $start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
                                                                    $end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
                                                                    $duration = number_format($row_rsTask_Start_Dates['duration']);
                                                                }
                                                        ?>
                                                                <tr id="row">
                                                                    <td style="width:5%"><?= $tcounter ?></td>
                                                                    <td style="width:40%"><?= $task_name ?></td>
                                                                    <td style="width:15%"><?= $unit_of_measure ?></td>
                                                                    <td style="width:10%"><?= $duration ?> Days</td>
                                                                    <td style="width:15%"><?= $start_date ?></td>
                                                                    <td style="width:15%"><?= $end_date ?></td>
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
                            <?php
                                }
                            }
                            ?>
                        </fieldset>
            <?php
                    }
                }
            }
            ?>
        </fieldset>
        <?php
    }
}

$query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1 AND projid = :projid");
$query_Output->execute(array(":projid" => $projid));
$total_Output = $query_Output->rowCount();
$outputs = '';
if ($total_Output > 0) {
    $outputs = '';
    if ($total_Output > 0) {
        $counter = 0;

        while ($row_rsOutput = $query_Output->fetch()) {
            $output_id = $row_rsOutput['id'];
            $output = $row_rsOutput['indicator_name'];
            $counter++;
            $site_id = 0;
        ?>
            <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    WAY POINT OUTPUT <?= $counter ?>: <?= $output ?>
                </legend>
                <?php
                $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ");
                $query_rsMilestone->execute(array(":output_id" => $output_id));
                $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                if ($totalRows_rsMilestone > 0) {
                    $task_counter = 0;
                    while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                        $milestone = $row_rsMilestone['milestone'];
                        $msid = $row_rsMilestone['msid'];
                        $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id ");
                        $query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0));
                        $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                        $edit = $totalRows_rsTask_Start_Dates > 1 ? 1 : 0;
                        $details = array("output_id" => $output_id, "site_id" => $site_id, 'task_id' => $msid, 'edit' => $edit);
                        $task_counter++;
                ?>
                        <div class="row clearfix">
                            <input type="hidden" name="task_amount[]" id="task_amount<?= $msid ?>" class="task_costs" value="<?= $sum_cost ?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card-header">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h5>
                                                <u>
                                                    TASK <?= $task_counter ?>: <?= $milestone ?>
                                                    <?php
                                                    if ($implementation_type == 1) {
                                                    ?>
                                                        <div class="btn-group" style="float:right">
                                                            <div class="btn-group" style="float:right">
                                                                <button type="button" data-toggle="modal" data-target="#outputItemModal" data-backdrop="static" data-keyboard="false" onclick="get_tasks(<?= htmlspecialchars(json_encode($details)) ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                    <?php echo $totalRows_rsTask_Start_Dates > 0 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </u>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th style="width:40%">Item</th>
                                                <th style="width:15%">Unit of Measure</th>
                                                <th style="width:10%">Duration</th>
                                                <th style="width:15%">Start Date</th>
                                                <th style="width:15%">End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid  ORDER BY parenttask");
                                            $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $msid));
                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                            if ($totalRows_rsTasks > 0) {
                                                $tcounter = 0;
                                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                    $tcounter++;
                                                    $task_name = $row_rsTasks['task'];
                                                    $task_id = $row_rsTasks['tkid'];
                                                    $unit =  $row_rsTasks['unit_of_measure'];
                                                    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                    $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                    $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                    $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                    $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id AND subtask_id=:subtask_id ");
                                                    $query_rsTask_Start_Dates->execute(array(':task_id' => $msid, ':site_id' => 0, ":subtask_id" => $task_id));
                                                    $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                    $start_date = $end_date = $duration =  "";
                                                    if ($totalRows_rsTask_Start_Dates > 0) {
                                                        $start_date = date("d M Y", strtotime($row_rsTask_Start_Dates['start_date']));
                                                        $end_date = date("d M Y", strtotime($row_rsTask_Start_Dates['end_date']));
                                                        $duration = number_format($row_rsTask_Start_Dates['duration']);
                                                    }
                                            ?>
                                                    <tr id="row<?= $tcounter ?>">
                                                        <td style="width:5%"><?= $tcounter ?></td>
                                                        <td style="width:40%"><?= $task_name ?></td>
                                                        <td style="width:15%"><?= $unit_of_measure ?></td>
                                                        <td style="width:10%"><?= $duration ?> Days</td>
                                                        <td style="width:15%"><?= $start_date ?> </td>
                                                        <td style="width:15%"><?= $end_date ?></td>
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
                <?php
                    }
                }
                ?>
            </fieldset>
<?php
        }
    }
}

} catch (\PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>