<?php
require '../vendor/autoload.php';
require 'Connection.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class Cron
{
    function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->openConnection();
    }


    public  function index()
    {
        $current_date = date("Y-m-d");
        $query_rsProj = $this->db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projstage=10 AND (projstatus<>5 AND projstatus<>6 AND projstatus<>2) ORDER BY projid ASC");
        $query_rsProj->execute();
        $row_rsProj = $query_rsProj->fetch();
        $count_projects = $query_rsProj->rowCount();

        if ($count_projects > 0) {
            do {
                $projid = $row_rsProj->projid;
                $query_rsMilestone =  $this->db->prepare("SELECT * FROM tbl_milestone WHERE status <> 5 AND projid = :projid");
                $query_rsMilestone->execute(array(":projid" => $projid));
                $totalRows_rsMilestones = $query_rsMilestone->rowCount();

                $project_status_array = [];
                if ($totalRows_rsMilestones > 0) {
                    while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                        $milestone_id =  $row_rsMilestone->msid;
                        $milestone_status = [];
                        $milestone_progress = 0;
                        $query_rsTasks =  $this->db->prepare("SELECT * FROM tbl_task WHERE status <> 5 AND msid=:milestone_id");
                        $query_rsTasks->execute(array(":milestone_id" => $milestone_id));
                        $totalRows_rsTasks = $query_rsTasks->rowCount();

                        if ($totalRows_rsTasks > 0) {
                            while ($row_rsTasks = $query_rsTasks->fetch()) {
                                $task_id = $row_rsTasks->tkid;
                                $current_progress =  $row_rsTasks->progress;
                                $monitored = $row_rsTasks->monitored;
                                $end_date =  date('Y-m-d', strtotime($row_rsTasks->edate));
                                $start_date =  date('Y-m-d', strtotime($row_rsTasks->sdate));
                                $task_progress = 0;
                                $task_status = 0;

                                $query_rsTask_checklist =  $this->db->prepare("SELECT * FROM `tbl_project_monitoring_checklist` WHERE taskid=:task_id ");
                                $query_rsTask_checklist->execute(array(":task_id" => $task_id));
                                $totalRows_rsTask_checklist = $query_rsTask_checklist->rowCount();

                                if ($monitored) {
                                    if ($totalRows_rsTask_checklist > 0) {
                                        $task_score = 0;
                                        while ($row_rsTask_checklist = $query_rsTask_checklist->fetch()) {
                                            $question_id = $row_rsTask_checklist->ckid;
                                            $query_rsMonitoring =  $this->db->prepare("SELECT * FROM `tbl_project_monitoring_checklist_score` WHERE taskid = :task_id AND checklistid =:question_id ORDER BY id DESC LIMIT 1");
                                            $query_rsMonitoring->execute(array(":task_id" => $task_id, ":question_id" => $question_id));
                                            $row_rsMonitoring = $query_rsMonitoring->fetch();
                                            $totalRows_rsMonitoring = $query_rsMonitoring->rowCount();
                                            $task_score += $totalRows_rsMonitoring > 0 ? $row_rsMonitoring->score : 0;
                                        }
                                        
                                        $task_progress = (($task_score / $totalRows_rsTask_checklist) / 10) * 100;
                                        $task_status = 5;
                                        if ($task_progress < 100) { 
                                            $task_status = ($task_progress == $current_progress || $end_date < $current_date) ? 11 : 4; 
                                        }
                                    }
                                } else {
                                    if ($current_date < $start_date) {
                                        $task_status = 3;
                                    } elseif ($current_date > $end_date) {
                                        $task_status = 11;
                                    } else  if ($current_date >= $start_date && $current_date <= $end_date) {
                                        $task_status = 4;
                                    }
                                }

                                $milestone_status[] = $task_status;
                                $update_task_status = $this->db->prepare("UPDATE tbl_task SET progress=:percentage, status=:status WHERE tkid=:taskid");
                                $update_task_status->execute(array(":percentage" => $task_progress, ":status" => $task_status, ":taskid" => $task_id));
                            }
                        }

                        $query_rsTasks_progress =  $this->db->prepare("SELECT SUM(progress) as progress, count(*) tasks FROM tbl_task WHERE msid=:milestone_id");
                        $query_rsTasks_progress->execute(array(":milestone_id" => $milestone_id));
                        $row_rsTasks_progress = $query_rsTasks_progress->fetch();

                        $mile_progress = $row_rsTasks_progress->progress != null ? $row_rsTasks_progress->progress : 0;
                        $tasks = $row_rsTasks_progress->progress != null ? $row_rsTasks_progress->tasks : 0;

                        $milestone_progress = $mile_progress / $tasks;

                        $mile_status = 5;
                        if (in_array(11, $milestone_status)) {
                            $mile_status = 11;
                        } else if (in_array(4, $milestone_status)) {
                            $mile_status = 4;
                        } else if (in_array(3, $milestone_status)  && in_array(5, $milestone_status)) {
                            $mile_status = 4;
                        } else if (in_array(3, $milestone_status)  && !in_array(5, $milestone_status)) {
                            $mile_status = 3;
                        }

                        $project_status_array[] = $mile_status;
                        $sqlupdate = $this->db->prepare("UPDATE tbl_milestone SET progress=:percentage, status=:milestoneStatus WHERE msid=:milestoneID");
                        $result = $sqlupdate->execute(array(":percentage" => $milestone_progress, ":milestoneStatus" => $mile_status, ":milestoneID" => $milestone_id));
                    }
                } 
                $project_status = 5;
                if (in_array(11, $project_status_array)) {
                    $project_status = 11;
                } else if (in_array(4, $project_status_array)) {
                    $project_status = 4;
                } else if (in_array(3, $project_status_array)  && in_array(5, $project_status_array)) {
                    $project_status = 4;
                } else if (in_array(3, $project_status_array)  && !in_array(5, $project_status_array)) {
                    $project_status = 3;
                }

                $sqlprojupdate = $this->db->prepare("UPDATE tbl_projects SET projstatus = :project_status WHERE projid =:projid");
                $updated = $sqlprojupdate->execute(array(':project_status' => $project_status,  ':projid' => $projid));
            } while ($row_rsProj = $query_rsProj->fetch());
        }
    }
}

$cron = new Cron();
$cron->index();
