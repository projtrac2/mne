<?php
$query_rsOutput = $db->prepare("SELECT i.indicator_name,i.indicator_mapping_type, d.id,i.indicator_unit, unit_type FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.projid=:projid ORDER BY d.id");
$query_rsOutput->execute(array(":projid" => $projid));
// $row_rsOutput = $query_rsOutput->fetch();
$totalRows_rsOutput = $query_rsOutput->rowCount();


if ($totalRows_rsOutput > 0) {
    $Ocounter = 0;
    while ($row_rsOutput = $query_rsOutput->fetch()) {
        $Ocounter++;
        $output_id = $row_rsOutput['id'];
        $mapping_type = $row_rsOutput['indicator_mapping_type'];
        $output_name = $row_rsOutput['indicator_name'];
        $unit_type = $row_rsOutput['unit_type'];
        $indicator_unit = $row_rsOutput['indicator_unit'];

        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = $indicator_unit");
        $query_rsIndUnit->execute();
        $row_rsIndUnit = $query_rsIndUnit->fetch();
        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
        $measurement_unit = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : "";
?>
        <div class="panel panel-primary">
            <div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $output_id ?>">
                <i class="fa fa-caret-down" aria-hidden="true"></i>
                <strong> Output <?= $Ocounter ?>:<span class=""><?= $output_name ?> </span> </strong>
            </div>
            <div class="collapse output<?php echo $output_id ?>" style="padding:5px">
                <?php
                // $responsible = get_output_responsible($projid, $output_id, $workflow_stage, $project_sub_stage, 1);
                $responsible = true;
                if ($responsible) {
                    if ($mapping_type == 1) {
                        $query_rsDesigns = $db->prepare("SELECT * FROM tbl_project_output_designs WHERE output_id =:outputid ORDER BY id");
                        $query_rsDesigns->execute(array(":outputid" => $output_id));
                        // $row_rsDesigns = $query_rsDesigns->fetch();
                        $totalRows_rsDesigns = $query_rsDesigns->rowCount();
                        if ($totalRows_rsDesigns > 0) {
                            while ($row_rsDesigns = $query_rsDesigns->fetch()) {
                                $design_id = $row_rsDesigns['id'];
                                $design_name = $row_rsDesigns['design'];
                                $output_id = $row_rsDesigns['output_id'];
                                $output_sites = $row_rsDesigns['sites'];
                                $sites = explode(",", $output_sites);
                                $sites_length = count($sites);
                                for ($i = 0; $i < $sites_length; $i++) {
                                    $site_id = $sites[$i];
                                    $query_Output_Sites = $db->prepare("SELECT * FROM tbl_project_sites d INNER JOIN tbl_state s ON s.id = d.state_id WHERE site_id = :id ");
                                    $query_Output_Sites->execute(array(":id" => $site_id));
                                    $total_Output_Sites = $query_Output_Sites->rowCount();

                                    if ($total_Output_Sites > 0) {
                                        while ($row_rsOutput_Sites = $query_Output_Sites->fetch()) {
                                            $design_counter++;
                                            $site_name = $row_rsOutput_Sites['site'];
                                            $state = $row_rsOutput_Sites['state'];
                                        }
                                    }
                                }
                                $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE design_id=:design_id ORDER BY parent ASC");
                                $query_rsMilestone->execute(array(":design_id" => $design_id));
                                $row_rsMilestone = $query_rsMilestone->fetch();
                                $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                if ($totalRows_rsMilestone > 0) {
                                    $mcounter = 0;
                                    do {
                                        $milestone_name = $row_rsMilestone['milestone'];
                                        $milestone_id = $row_rsMilestone['msid'];
                                        $milestone_location = $row_rsMilestone['location'];
                                        $milestone_sequence = $row_rsMilestone['parent'];
                ?>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-list-ol" aria-hidden="true"></i> <?= $milestone_name ?>
                                            </legend>
                                            <?php
                                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone ORDER BY parenttask");
                                            $query_rsTasks->execute(array(":milestone" => $milestone_id));
                                            $row_rsTasks = $query_rsTasks->fetch();
                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                            if ($totalRows_rsTasks > 0) {
                                                $mcounter++;
                                                $tcounter = 0;
                                                do {
                                                    $task_name = $row_rsTasks['task'];
                                                    $task_id = $row_rsTasks['tkid'];
                                                    $task_duration = $row_rsTasks['duration'];
                                                    $task_sequence = $row_rsTasks['parenttask'];

                                                    $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_task_parameters WHERE task_id=:task_id");
                                                    $query_rsTask_parameters->execute(array(":task_id" => $task_id));
                                                    $row_rsTask_parameters = $query_rsTask_parameters->fetch();
                                                    $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();

                                                    if ($totalRows_rsTask_parameters > 0) {
                                                        $tcounter++;
                                                        $budget_line_rowno = 0;
                                                        do {
                                                            $budget_line_rowno++;
                                                            $task_parameter_name = $row_rsTask_parameters['parameter'];
                                                            $unit_of_measure = $row_rsTask_parameters['unit_of_measure'];
                                                            $task_parameter_id = $row_rsTask_parameters['id'];
                                                        } while ($row_rsTask_parameters = $query_rsTask_parameters->fetch());
                                                    }
                                                } while ($row_rsTasks = $query_rsTasks->fetch());
                                            }
                                            ?>
                                        </fieldset>
                                    <?php
                                    } while ($row_rsMilestone = $query_rsMilestone->fetch());
                                }
                            }
                        }
                    } else {
                        $query_Output_States = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
                        $query_Output_States->execute(array(":output_id" => $output_id));
                        $total_Output_States = $query_Output_States->rowCount();
                        if ($total_Output_States > 0) {
                            $query_rsDesigns = $db->prepare("SELECT * FROM tbl_project_output_designs WHERE output_id =:outputid ORDER BY id");
                            $query_rsDesigns->execute(array(":outputid" => $output_id));
                            $Rows_rsDesigns = $query_rsDesigns->fetch();
                            $totalRows_rsDesigns = $query_rsDesigns->rowCount();
                            $design_name = $totalRows_rsDesigns > 0 ? $Rows_rsDesigns['design'] : "";
                            $design_id = $totalRows_rsDesigns > 0 ? $Rows_rsDesigns['id'] : "";
                            $output_states = [];
                            while ($row_Output_States = $query_Output_States->fetch()) {
                                $output_states[] = $row_Output_States['state'];
                            }

                            $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE design_id=:design_id ORDER BY parent ASC");
                            $query_rsMilestone->execute(array(":design_id" => $design_id));
                            $row_rsMilestone = $query_rsMilestone->fetch();
                            $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                            if ($totalRows_rsMilestone > 0) {
                                $mcounter = 0;
                                do {
                                    $milestone_name = $row_rsMilestone['milestone'];
                                    $milestone_id = $row_rsMilestone['msid'];
                                    $milestone_location = $row_rsMilestone['location'];
                                    $milestone_sequence = $row_rsMilestone['parent'];
                                    ?>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-list-ol" aria-hidden="true"></i> <?= $milestone_name ?>
                                        </legend>
                                        <?php
                                        $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:milestone ORDER BY parenttask");
                                        $query_rsTasks->execute(array(":milestone" => $milestone_id));
                                        $row_rsTasks = $query_rsTasks->fetch();
                                        $totalRows_rsTasks = $query_rsTasks->rowCount();
                                        if ($totalRows_rsTasks > 0) {
                                            $mcounter++;
                                            $tcounter = 0;
                                            do {
                                                $task_name = $row_rsTasks['task'];
                                                $task_id = $row_rsTasks['tkid'];
                                                $task_duration = $row_rsTasks['duration'];
                                                $task_sequence = $row_rsTasks['parenttask'];

                                                $query_rsTask_parameters = $db->prepare("SELECT * FROM tbl_task_parameters WHERE task_id=:task_id");
                                                $query_rsTask_parameters->execute(array(":task_id" => $task_id));
                                                $row_rsTask_parameters = $query_rsTask_parameters->fetch();
                                                $totalRows_rsTask_parameters = $query_rsTask_parameters->rowCount();

                                                if ($totalRows_rsTask_parameters > 0) {
                                                    $tcounter++;
                                                    $budget_line_rowno = 0;
                                                    do {
                                                        $budget_line_rowno++;
                                                        $task_parameter_name = $row_rsTask_parameters['parameter'];
                                                        $unit_of_measure = $row_rsTask_parameters['unit_of_measure'];
                                                        $task_parameter_id = $row_rsTask_parameters['id'];
                                                    } while ($row_rsTask_parameters = $query_rsTask_parameters->fetch());
                                                }
                                            } while ($row_rsTasks = $query_rsTasks->fetch());
                                        }
                                        ?>
                                    </fieldset>
                <?php
                                } while ($row_rsMilestone = $query_rsMilestone->fetch());
                            }
                        }
                    }
                }

                ?>
            </div>
        </div>
<?php
    }
}
