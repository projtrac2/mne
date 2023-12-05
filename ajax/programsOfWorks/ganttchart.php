<?php
include '../controller.php';
try {




    function timeline_chart($projid, $site_id)
    {
        global $db;

        $query_rsMyP =  $db->prepare("SELECT *, projcost, projstartdate AS sdate, projenddate AS edate, projcategory, progress FROM tbl_projects WHERE deleted='0' AND projid = :projid ");
        $query_rsMyP->execute(array(":projid" => $projid));
        $row_rsMyP = $query_rsMyP->fetch();
        $projname = $row_rsMyP['projname'];


        $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid AND site_id=:site_id");
        $query_rsTask_Start_Dates->execute(array(":projid" => $projid, ":site_id" => $site_id));
        $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
        if (!is_null($row_rsTask_Start_Dates['start_date']) && !is_null($row_rsTask_Start_Dates['end_date'])) {
            $project_data = "
            [{
                name: '$projname',";

            $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
            $query_Output->execute(array(":projid" => $projid));
            $total_Output = $query_Output->rowCount();
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $output_id = $row_rsOutput['id'];
                    $output = $row_rsOutput['indicator_name'];
                    $query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE outputid=:output_id ORDER BY parent ASC");
                    $query_rsMilestone->execute(array(":output_id" => $output_id));
                    $totalRows_rsMilestone = $query_rsMilestone->rowCount();

                    $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE output_id=:output_id AND site_id=:site_id");
                    $query_rsTask_Start_Dates->execute(array(":output_id" => $output_id, ":site_id" => $site_id));
                    $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                    if (!is_null($row_rsTask_Start_Dates['start_date']) && !is_null($row_rsTask_Start_Dates['end_date'])) {
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

                                $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE task_id=:task_id AND site_id=:site_id");
                                $query_rsTask_Start_Dates->execute(array(":task_id" => $milestone_id, ":site_id" => $site_id));
                                $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                if (!is_null($row_rsTask_Start_Dates['start_date']) && !is_null($row_rsTask_Start_Dates['end_date'])) {
                                    $start_date =  strtotime($row_rsTask_Start_Dates['start_date']) * 1000;
                                    $end_date =  strtotime($row_rsTask_Start_Dates['end_date']) * 1000;

                                    $project_data .=
                                        "{
                                        id: 'task_$milestone_id',
                                        name: '$milestone_name',
                                        start: $start_date,
                                        end:$end_date,
                                        parent: 'output_$output_id'
                                    },";


                                    $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id AND msid=:msid");
                                    $query_rsTasks->execute(array(":output_id" => $output_id, ":msid" => $milestone_id));
                                    $totalRows_rsTasks = $query_rsTasks->rowCount();
                                    if ($totalRows_rsTasks > 0) {
                                        while ($row_rsTasks = $query_rsTasks->fetch()) {
                                            $task_name = $row_rsTasks['task'];
                                            $task_id = $row_rsTasks['tkid'];
                                            $parent =  $row_rsTasks['parenttask'];

                                            $query_rsTask_Start_Dates = $db->prepare("SELECT start_date,end_date FROM tbl_program_of_works WHERE subtask_id=:subtask_id AND site_id=:site_id");
                                            $query_rsTask_Start_Dates->execute(array(":subtask_id" => $task_id, ":site_id" => $site_id));
                                            $row_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();
                                            $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

                                            if ($totalRows_rsTask_Start_Dates > 0) {
                                                $start_date = strtotime($row_rsTask_Start_Dates['start_date']) * 1000;
                                                $end_date =  strtotime($row_rsTask_Start_Dates['end_date']) *  1000;
                                                $project_data .=
                                                    "{
                                                    id: 'subtask_$task_id',
                                                    name: '$task_name',
                                                    start: $start_date,
                                                    end:$end_date,
                                                    dependency: 'subtask_$parent',
                                                    parent: 'task_$milestone_id',
                                                    task_id:$task_id
                                                },";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $project_data .= "],";
                    }
                }
            }
        }

        $project_data .= "}]";
        return $project_data;
    }

    if (isset($_GET['timeline_series'])) {
        $site_id = $_GET['site_id'];
        $projid = $_GET['projid'];
        $project_data = timeline_chart($projid, $site_id);
        echo json_encode(array("success" => true, "series" => $project_data, "data" => $data));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
