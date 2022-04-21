<?php
require '../vendor/autoload.php';
require 'Connection.php';
class CronJob
{
    protected $current_date, $db;
    public function __construct()
    {
        $this->current_date = date("Y-m-d");
        $conn = new Connection();
        $this->db = $conn->openConnection();
    }

    public function index()
    {
        // get projects
        $projects = $this->get_projects();
        if ($projects) {
            foreach ($projects as $project) {
                $projid = $project->projid;
                $projstage = $project->projstage;
                $projcategory = $project->projcategory;
                $implimentation_type = $project->projcategory;
                // Task update 
                $task_update = $this->progress_update($projid);

                // Milestone Update status
                $milestone_update = $this->update_milestone_status($projid, $implimentation_type);

                // project status
                $sqlprojupdate = $this->db->prepare("UPDATE tbl_projects SET projstatus = :projStatus WHERE projid =:projectID");
                $updated = $sqlprojupdate->execute(array(':projStatus' => $milestone_update,  ':projectID' => $projid));

                $monitoring_date = $this->update_next_monitoring_date($projid);
            }
        }
    }

    // get projects 
    public function get_projects()
    {
        $query_rsProj = $this->db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projstage=10 AND (projstatus<>5 AND projstatus<>6 AND projstatus<>2) ORDER BY projid ASC");
        $query_rsProj->execute();
        $row_rsProj = $query_rsProj->fetchAll();
        $count_projects = $query_rsProj->rowCount();
        if ($count_projects > 0) {
            return $row_rsProj;
        } else {
            return false;
        }
    }

    // get Projects outputs
    public function get_outputs($projid)
    {
        $query_rsOutputs = $this->db->prepare("SELECT * FROM tbl_project_details WHERE projid='$projid' ORDER BY id ASC");
        $query_rsOutputs->execute();
        $row_rsOutputs = $query_rsOutputs->fetchAll();
        $count_proOutputss = $query_rsOutputs->rowCount();
        if ($count_proOutputss > 0) {
            return $row_rsOutputs;
        } else {
            return false;
        }
    }

    // get tasks 
    public function tasks($where)
    {
        $query_rsTasks =  $this->db->prepare("SELECT * FROM tbl_task WHERE $where");
        $query_rsTasks->execute();
        $row_rsTasks = $query_rsTasks->fetchAll();
        $totalRows_rsTasks = $query_rsTasks->rowCount();
        if ($totalRows_rsTasks > 0) {
            return $row_rsTasks;
        } else {
            return false;
        }
    }

    // get locations 
    public function get_locations($output_id)
    {
        $query_rsStates =  $this->db->prepare("SELECT * FROM  tbl_output_disaggregation WHERE  outputid='$output_id' ");
        $query_rsStates->execute();
        $row_rsStates = $query_rsStates->fetchAll();
        $totalRows_rsStates = $query_rsStates->rowCount();
        if ($totalRows_rsStates > 0) {
            return $row_rsStates;
        } else {
            return false;
        }
    }

    public function get_task_questions($task_id)
    {
        $query_rsTasks =  $this->db->prepare("SELECT * FROM `tbl_project_monitoring_checklist` WHERE taskid='$task_id' ");
        $query_rsTasks->execute();
        $row_rsTasks = $query_rsTasks->fetchAll();
        $totalRows_rsTasks = $query_rsTasks->rowCount();
        if ($totalRows_rsTasks > 0) {
            return $row_rsTasks;
        } else {
            return false;
        }
    }

    public function monitoring($task_id, $location_id, $question_id)
    {
        $query_rsMonitoring =  $this->db->prepare("SELECT * FROM `tbl_project_monitoring_checklist_score` WHERE taskid = '$task_id' AND checklistid = '$question_id' AND level3='$location_id' ORDER BY id DESC LIMIT 1");
        $query_rsMonitoring->execute();
        $row_rsMonitoring = $query_rsMonitoring->fetch();
        $totalRows_rsMonitoring = $query_rsMonitoring->rowCount();

        $score = 0;
        if ($totalRows_rsMonitoring > 0) {
            $score = !empty($row_rsMonitoring->score) ? $row_rsMonitoring->score : 0;
        }
        return $score;
    }

    public function update_task_status($task_id, $percentage, $status_id)
    {
        $update_status = $this->db->prepare("UPDATE tbl_task SET progress=:percentage, status=:status WHERE tkid=:taskid");
        $result = $update_status->execute(array(":percentage" => $percentage, ":status" => $status_id, ":taskid" => $task_id));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update_milestone_status($projid, $implimentation_type)
    {
        $query_rsMilestone =  $this->db->prepare("SELECT * FROM tbl_milestone WHERE status <> 2 AND status <> 6 AND projid = '$projid' ");
        $query_rsMilestone->execute(array(":projid" => $projid));
        $project_status_arr = array();

        while ($row_rsMilestone = $query_rsMilestone->fetch()) {
            $milestoneID =  $row_rsMilestone->msid;
            $mlStatus =  $row_rsMilestone->status;
            $output_id =  $row_rsMilestone->outputid;

            $milestone_task_status = array();
            $where = "status <> 2  AND status <> 6 AND projid = '$projid' AND msid=$milestoneID";
            $output_tasks = $this->tasks($where);
            $total_tasks = $output_tasks ? count($output_tasks) : 0;
            $task_progress = 0;
 
            if ($total_tasks > 0) {
                foreach ($output_tasks as $output_task) {
                    $task_status = $output_task->status;
                    $task_progress += $output_task->progress;
                    $milestone_task_status[] = $task_status;
                }
            }

            $milestone_status  = 5;
            $percentage = $total_tasks > 0 ? ($task_progress / $total_tasks) : 0; 
            if ($percentage < 100) {
                if (in_array(11, $milestone_task_status)) {
                    $milestone_status = 11;
                } else if (in_array(3, $milestone_task_status) && in_array(5, $milestone_task_status)) {
                    $milestone_status = 4;
                } else if (in_array(3, $milestone_task_status) && in_array(4, $milestone_task_status)) {
                    $milestone_status = 4;
                } else if (in_array(3, $milestone_task_status) && !in_array(4, $milestone_task_status)) {
                    $milestone_status = 3;
                }
            }

            $project_status_arr[] = $milestone_status;
            $sqlupdate = $this->db->prepare("UPDATE tbl_milestone SET progress=:percentage, status=:milestoneStatus WHERE msid=:milestoneID");
            $result = $sqlupdate->execute(array(":percentage" => $percentage, ":milestoneStatus" => $milestone_status, ":milestoneID" => $milestoneID));

            $paymentrequired = $row_rsMilestone->paymentrequired;
            if ($milestone_status == 5 && $implimentation_type == 2 && $paymentrequired == 1) {
                $certificate_number = "UG-" . date('Y') . "-" . rand(30, 40000);
                $sqlupdate = $this->db->prepare("INSERT INTO tbl_milestone_certificate (projid, msid, certificate_no, created_at) VALUES(:projid, :msid, :certificate_no, :created_at)");
                $result = $sqlupdate->execute(array(":projid" => $projid, ":msid" => $milestoneID, ":certificate_no" => $certificate_number, ":created_at" => $this->current_date));
            }
        }

        $project_status = 5;
        if (in_array(11, $project_status_arr)) {
            $project_status = 11;
        } else if (in_array(3, $project_status_arr) && in_array(5, $project_status_arr)) {
            $project_status = 4;
        } else if (in_array(3, $project_status_arr) && in_array(4, $project_status_arr)) {
            $project_status = 4;
        } else if (in_array(3, $project_status_arr) && !in_array(4, $project_status_arr)) {
            $project_status = 3;
        }
        return $project_status;
    }

    public function progress_update($projid)
    {
        // get projects outputs
        $project_outputs = $this->get_outputs($projid);
        if ($project_outputs) {
            foreach ($project_outputs as $project_output) {
                $output_id = $project_output->id;
                // get Project output Tasks  
                $where = "status <> 2 AND status <> 5 AND status <> 6 AND outputid = '$output_id'";
                $output_tasks = $this->tasks($where);
                if ($output_tasks) {
                    foreach ($output_tasks as $output_task) {
                        $task_id = $output_task->tkid;
                        $task_progress =  $output_task->progress;
                        $monitored = $output_task->monitored;
                        $location_score_per_question = 0;
                        $task_progress = 0;
                        $task_status = 4;
                        $end_date =  $output_task->edate;
                        $start_date =  $output_task->sdate;
                        if ($monitored) {
                            // get Projecs output locations 
                            $output_locations = $this->get_locations($output_id);
                            $questions = $this->get_task_questions($task_id);
                            $total_locations = ($questions) ?  count($output_locations) : 0;
                            $total_questions = ($questions) ? count($questions) : 0;
                            if ($output_locations) {
                                foreach ($output_locations as $output_location) {
                                    $location_id = $output_location->outputstate;
                                    // get task questions 
                                    if ($questions) {
                                        foreach ($questions as $question) {
                                            $question_id = $question->ckid;
                                            $location_score_per_question += $this->monitoring($task_id, $location_id, $question_id);
                                        }
                                    }
                                }
                            }

                            if ($total_locations > 0 && $total_questions > 0) {
                                $task_progress = (($location_score_per_question / 10) / ($total_locations * $total_questions)) * 100;
                            }

                            $task_status = 5;
                            if ($task_progress < 100) {
                                $task_status = 11;
                                if ($task_progress > 0) {
                                    $task_status = 4;
                                    if ($end_date < $this->current_date) {
                                        $task_status = 11;
                                    }
                                }
                            }
                        } else {
                            if ($this->current_date < $start_date) {
                                $task_status = 3;
                            } else  if ($this->current_date >= $start_date && $this->current_date <= $end_date) {
                                $task_status = 4;
                                echo $task_status . " status" . $end_date;
                            } elseif ($this->current_date > $end_date) {
                                $task_status = 11;
                            }
                        }
                        $status_update = $this->update_task_status($task_id, $task_progress, $task_status);
                    }
                }
            }
        }
        return;
    }

    // function to update the next monitoring date
    public function update_next_monitoring_date($projid)
    {
        $query_monitoringfreq =  $this->db->prepare("SELECT opid, frequency, days, outputid FROM tbl_project_outputs_mne_details o inner join tbl_datacollectionfreq f on f.fqid=o.monitoring_frequency WHERE o.projid = :projid");
        $query_monitoringfreq->execute(array(":projid" => $projid));
        $totalRows_monitoringfreq = $query_monitoringfreq->rowCount();


        $result = array();
        if ($totalRows_monitoringfreq > 0) {
            while ($row_monitoringfreq = $query_monitoringfreq->fetch()) {
                $monfreq = $row_monitoringfreq->days;
                $opid = $row_monitoringfreq->opid;
                $outputid = $row_monitoringfreq->outputid;

                $query_milestonestartdate =  $this->db->prepare("SELECT MIN(sdate) as earliestopdate FROM tbl_milestone WHERE projid = :projid and outputid=:outputid");
                $query_milestonestartdate->execute(array(":projid" => $projid, ":outputid" => $outputid));
                $row_milestonestartdate = $query_milestonestartdate->fetch();
                $milestonestartdate = $row_milestonestartdate->earliestopdate;

                $monitoringdate = date('Y-m-d', strtotime("+" . $monfreq, strtotime($milestonestartdate)));

                $query_monitoringdate =  $this->db->prepare("SELECT next_monitoring_date FROM tbl_project_outputs_mne_details WHERE opid = :opid");
                $query_monitoringdate->execute(array(":opid" => $opid));
                $row_monitoringdate = $query_monitoringdate->fetch();
                $projmonitoringdate = date('Y-m-d', strtotime($row_monitoringdate->next_monitoring_date));

                if ($projmonitoringdate < $this->current_date) {
                    $sqlUpdate = $this->db->prepare("UPDATE tbl_project_outputs_mne_details SET next_monitoring_date = :opmonitoringdate WHERE opid =:opid");
                    $update = $sqlUpdate->execute(array(':opmonitoringdate' => $monitoringdate, ':opid' => $opid));
                    $result[] = ($update) ? true : false;
                }
            }
        }

        if (!in_array(false, $result)) {
            return true;
        } else {
            return false;
        }
    }
}


$cron = new CronJob();
$cron->index();
