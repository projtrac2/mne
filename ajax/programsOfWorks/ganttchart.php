<?php
include '../controller.php';
try {
    if (isset($_GET['timeline_series'])) {
        $site_id = $_GET['site_id'];
        $projid = $_GET['projid'];
        $query_rsMyP =  $db->prepare("SELECT *, projcost, projstartdate AS sdate, projenddate AS edate, projcategory, progress FROM tbl_projects WHERE deleted='0' AND projid =:projid");
        $query_rsMyP->execute(array(":projid" => $projid));
        $row_rsMyP = $query_rsMyP->fetch();
        $project_data = "";
        $success = false;
        if ($row_rsMyP) {
            $projname = $row_rsMyP['projname'];
            if ($site_id != '') {
                $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid ");
                $query_rsTask_Start_Dates->execute(array(":projid" => $projid));
                $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                $roject_start_date = '';
                $project_end_date = '';
                if ($totalRows_rsTask_Start_Dates > 0) {
                    $project_start_date = $row_rsTask_Start_Dates['start_date'];
                    $project_end_date = $row_rsTask_Start_Dates['end_date'];
                    $project_data = "[{name: '$projname',";
                    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_output_disaggregation s ON d.id = s.output_id  INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.projid = :projid AND s.output_site=:site_id");
                    $query_Output->execute(array(":projid" => $projid, ":site_id" => $site_id));
                    $total_Output = $query_Output->rowCount();
                    $output_array = [];
                    if ($total_Output > 0) {
                        $outputs = [];
                        $success = true;
                        while ($row_rsOutput = $query_Output->fetch()) {
                            $output_id = $row_rsOutput['id'];
                            $output = $row_rsOutput['indicator_name'];
                            $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
                            $query_rsMilestone->execute(array(":output_id" => $output_id));
                            $totalRows_rsMilestone = $query_rsMilestone->rowCount();

                            $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE output_id=:output_id  AND site_id=:site_id");
                            $query_rsTask_Start_Dates->execute(array(":output_id" => $output_id, ":site_id" => $site_id));
                            $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                            if ($totalRows_rsTask_Start_Dates > 0) {
                                $start_date =  strtotime($row_rsTask_Start_Dates['start_date']) * 1000;
                                $end_date =  strtotime($row_rsTask_Start_Dates['end_date']) * 1000;

                                $project_data .= "
                            data: [{
                                id: 'output_$output_id',
                                name: '$output',
                                start: $start_date,
                                end:$end_date,
                            },";
                                if ($totalRows_rsMilestone > 0) {
                                    while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                        $milestone_name = $row_rsMilestone['milestone'];
                                        $milestone_id = $row_rsMilestone['msid'];

                                        $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE task_id=:task_id  AND site_id=:site_id");
                                        $query_rsTask_Start_Dates->execute(array(":task_id" => $milestone_id, ":site_id" => $site_id));
                                        $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                        $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                        if ($totalRows_rsTask_Start_Dates > 0) {
                                            $start_date =  strtotime($row_rsTask_Start_Dates['start_date']) * 1000;
                                            $end_date =  strtotime($row_rsTask_Start_Dates['end_date']) * 1000;

                                            $project_data .= "{
                                            id: 'task_$milestone_id',
                                            name: '$milestone_name',
                                            start: $start_date,
                                            end:$end_date,
                                            parent: 'output_$output_id'
                                        },";


                                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid");
                                            $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $milestone_id));
                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                            $subtasks_array = [];
                                            if ($totalRows_rsTasks > 0) {
                                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                    $task_name = $row_rsTasks['task'];
                                                    $task_id = $row_rsTasks['tkid'];
                                                    $unit =  $row_rsTasks['unit_of_measure'];
                                                    $parent =  $row_rsTasks['parenttask'];
                                                    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                    $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                    $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                    $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                    $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE subtask_id=:subtask_id  AND site_id=:site_id");
                                                    $query_rsTask_Start_Dates->execute(array(":subtask_id" => $task_id, ":site_id" => $site_id));
                                                    $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                    if ($totalRows_rsTask_Start_Dates > 0) {
                                                        $start_date = strtotime($row_rsTask_Start_Dates['start_date']) * 1000;
                                                        $end_date =  strtotime($row_rsTask_Start_Dates['end_date']) *  1000;
                                                        $project_data .= "{
                                                        id: 'subtask_$task_id',
                                                        name: '$task_name',
                                                        start: $start_date,
                                                        end:$end_date,
                                                        dependency: 'subtask_$parent',
                                                        parent: 'task_$milestone_id'
                                                    },";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $project_data .= "]";
                            }
                        }
                    }
                }
                $project_data .= "}]";
            } else {
                $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid ");
                $query_rsTask_Start_Dates->execute(array(":projid" => $projid));
                $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                $roject_start_date = '';
                $project_end_date = '';
                if ($totalRows_rsTask_Start_Dates > 0) {
                    $project_start_date = $row_rsTask_Start_Dates['start_date'];
                    $project_end_date = $row_rsTask_Start_Dates['end_date'];
                    $project_data = "[{name: '$projname',";
                    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
                    $query_Output->execute(array(":projid" => $projid));
                    $total_Output = $query_Output->rowCount();
                    $output_array = [];
                    if ($total_Output > 0) {
                        $outputs = [];
                        $success = true;
                        while ($row_rsOutput = $query_Output->fetch()) {
                            $output_id = $row_rsOutput['id'];
                            $output = $row_rsOutput['indicator_name'];
                            $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
                            $query_rsMilestone->execute(array(":output_id" => $output_id));
                            $totalRows_rsMilestone = $query_rsMilestone->rowCount();

                            $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE output_id=:output_id ");
                            $query_rsTask_Start_Dates->execute(array(":output_id" => $output_id));
                            $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                            if ($totalRows_rsTask_Start_Dates > 0) {
                                $start_date =  strtotime($row_rsTask_Start_Dates['start_date']) * 1000;
                                $end_date =  strtotime($row_rsTask_Start_Dates['end_date']) * 1000;

                                $project_data .= "
                                data: [{
                                    id: 'output_$output_id',
                                    name: '$output',
                                    start: $start_date,
                                    end:$end_date,
                                },";
                                if ($totalRows_rsMilestone > 0) {
                                    while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                        $milestone_name = $row_rsMilestone['milestone'];
                                        $milestone_id = $row_rsMilestone['msid'];

                                        $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE task_id=:task_id ");
                                        $query_rsTask_Start_Dates->execute(array(":task_id" => $milestone_id));
                                        $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                        $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                        if ($totalRows_rsTask_Start_Dates > 0) {
                                            $start_date =  strtotime($row_rsTask_Start_Dates['start_date']) * 1000;
                                            $end_date =  strtotime($row_rsTask_Start_Dates['end_date']) * 1000;

                                            $project_data .= "{
                                                id: 'task_$milestone_id',
                                                name: '$milestone_name',
                                                start: $start_date,
                                                end:$end_date,
                                                parent: 'output_$output_id'
                                            },";

                                            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid");
                                            $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $milestone_id));
                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                            $subtasks_array = [];
                                            if ($totalRows_rsTasks > 0) {
                                                while ($row_rsTasks = $query_rsTasks->fetch()) {
                                                    $task_name = $row_rsTasks['task'];
                                                    $task_id = $row_rsTasks['tkid'];
                                                    $unit =  $row_rsTasks['unit_of_measure'];
                                                    $parent =  $row_rsTasks['parenttask'];
                                                    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                    $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                    $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                    $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                    $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE subtask_id=:subtask_id ");
                                                    $query_rsTask_Start_Dates->execute(array(":subtask_id" => $task_id));
                                                    $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                                    $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();
                                                    if ($totalRows_rsTask_Start_Dates > 0) {
                                                        $start_date = strtotime($row_rsTask_Start_Dates['start_date']) * 1000;
                                                        $end_date =  strtotime($row_rsTask_Start_Dates['end_date']) *  1000;
                                                        $project_data .= "{
                                                            id: 'subtask_$task_id',
                                                            name: '$task_name',
                                                            start: $start_date,
                                                            end:$end_date,
                                                            dependency: 'subtask_$parent',
                                                            parent: 'task_$milestone_id'
                                                        },";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $project_data .= "]";
                            }
                        }
                    }
                }

                $project_data .= "}]";
            }
        }

        echo json_encode(array("success" => $success, "series" => $project_data));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
