<?php

function get_achieved($site_id, $task_id, $subtask_id, $start_date, $end_date)
{
    global $db;
    $stmt = $db->prepare('SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score WHERE site_id=:site_id AND task_id=:task_id AND subtask_id=:subtask_id AND created_at >=:start_date  AND created_at <=:end_date');
    $stmt->execute(array(':site_id' => $site_id, ':task_id' => $task_id, ":subtask_id" => $subtask_id, ":start_date" => $start_date, ":end_date" => $end_date));
    $result = $stmt->fetch();
    return !is_null($result['achieved'])  ? true : false;
}

function get_reminder_table($projid, $site_id, $output_id, $end_date, $member_id)
{
    global $db;
    $query_rsFrequency = $db->prepare("SELECT * FROM tbl_project_monitoring_frequencies f INNER JOIN tbl_task t ON t.tkid=f.subtask_id INNER JOIN tbl_member_subtasks s ON s.subtask_id=f.tbl_member_subtasks WHERE f.projid=:projid AND f.site_id=:site_id AND f.output_id=:output_id AND f.end_date=:end_date AND member_id=:member_id");
    $query_rsFrequency->execute(array(':projid' => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":end_date" => $end_date, ":member_id" => $member_id));
    $rows_rsFrequency = $query_rsFrequency->rowCount();

    $table = '';
    if ($rows_rsFrequency > 0) {
        $counter = 0;
        $table_body = '';
        while ($row = $query_rsFrequency->fetch()) {
            $counter++;
            $end_date = $row['end_date'];
            $site_id = $row['site_id'];
            $table_body .= '<tr><td>' . $counter . '</td><td>' . $row['task'] . '</td></tr>';
        }
        $table =
            '<div class="table-responsive">
                    <table class="table table-bordered js-basic-example dataTable" id="direct_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $table_body . '
                        </tbody>
                    </table>
                </div>';
    }
    return $table;
}

function check_reminder($projid, $start_date, $member_id)
{
    global $db;
    $detail = '';
    $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
    $query_Sites->execute(array(":projid" => $projid));
    $rows_sites = $query_Sites->rowCount();
    if ($rows_sites > 0) {
        $counter = 0;
        while ($row_Sites = $query_Sites->fetch()) {
            $site_id = $row_Sites['site_id'];
            $site = $row_Sites['site'];
            $output_detail = '';

            $details = [];
            $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
            $query_Site_Output->execute(array(":site_id" => $site_id));
            $rows_Site_Output = $query_Site_Output->rowCount();
            if ($rows_Site_Output > 0) {
                $output_counter = 0;
                while ($row_Site_Output = $query_Site_Output->fetch()) {
                    $output_id = $row_Site_Output['outputid'];
                    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                    $query_Output->execute(array(":outputid" => $output_id));
                    $row_Output = $query_Output->fetch();
                    $total_Output = $query_Output->rowCount();
                    $output = ($total_Output > 0) ? $row_Output['indicator_name'] : '';

                    $table =   get_reminder_table($projid, $site_id, $output_id, $start_date, $member_id);
                    if ($table != '') {
                        $details[] = true;
                        $output_counter++;
                        $output_detail .= '
                            <fieldset class="scheduler-border">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                Output ' . $output_counter . ' : ' . $output . '
                            </legend>
                                    ' . $table . '
                            </fieldset>';
                    }
                }
            }

            if (!empty($details) && in_array(true, $details)) {
                $counter++;
                $detail .= '
                <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    SITE ' . $counter . ' : ' . $site . '
                </legend>
                ' . $output_detail . '
                </fieldset>';
            }
        }
    }

    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1 AND projid = :projid");
    $query_Output->execute(array(":projid" => $projid));
    $total_Output = $query_Output->rowCount();
    if ($total_Output > 0) {
        $site_id = 0;
        $output_counter = 0;
        while ($row_rsOutput = $query_Output->fetch()) {
            $output_id = $row_rsOutput['id'];
            $output = $row_rsOutput['indicator_name'];
            $table =   get_reminder_table($projid, $site_id, $output_id, $start_date, $member_id);
            if ($table != '') {
                $output_counter++;
                $detail .= '
                <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    Output ' . $output_counter . ' : ' . $output . '
                </legend>
                        ' . $table . '
                </fieldset>';
            }
        }
    }

    return $detail;
}

function get_escalation_table($projid, $site_id, $output_id, $end_date, $member_id)
{
    global $db;
    $query_rsFrequency = $db->prepare("SELECT * FROM tbl_project_monitoring_frequencies f INNER JOIN tbl_task t ON t.tkid=f.subtask_id INNER JOIN tbl_member_subtasks s ON s.subtask_id=f.tbl_member_subtasks WHERE f.projid=:projid AND f.site_id=:site_id AND f.output_id=:output_id AND f.end_date=:end_date AND member_id=:member_id");
    $query_rsFrequency->execute(array(':projid' => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":end_date" => $end_date, ":member_id" => $member_id));
    $rows_rsFrequency = $query_rsFrequency->rowCount();

    $table_body = '';
    if ($rows_rsFrequency > 0) {
        $counter = 0;
        while ($row = $query_rsFrequency->fetch()) {
            $counter++;
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $site_id = $row['site_id'];
            $task_id = $row['task_id'];
            $subtask_id = $row['subtask_id'];
            $achieved = get_achieved($site_id, $task_id, $subtask_id, $start_date, $end_date);
            if (!$achieved) {
                $table_body .= '<tr><td>' . $counter . '</td><td>' . $row['task'] . '</td></tr>';
            }
        }
        $table =
            '<div class="table-responsive">
                    <table class="table table-bordered js-basic-example dataTable" id="direct_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $table_body . '
                        </tbody>
                    </table>
                </div>';
    }

    return $table_body;
}

function check_escalation($projid, $end_date, $member_id)
{
    global $db;
    $detail = '';
    $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
    $query_Sites->execute(array(":projid" => $projid));
    $rows_sites = $query_Sites->rowCount();
    if ($rows_sites > 0) {
        $counter = 0;
        while ($row_Sites = $query_Sites->fetch()) {
            $site_id = $row_Sites['site_id'];
            $site = $row_Sites['site'];
            $output_detail = '';

            $details = [];
            $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
            $query_Site_Output->execute(array(":site_id" => $site_id));
            $rows_Site_Output = $query_Site_Output->rowCount();
            if ($rows_Site_Output > 0) {
                $output_counter = 0;
                while ($row_Site_Output = $query_Site_Output->fetch()) {
                    $output_id = $row_Site_Output['outputid'];
                    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                    $query_Output->execute(array(":outputid" => $output_id));
                    $row_Output = $query_Output->fetch();
                    $total_Output = $query_Output->rowCount();
                    $output = ($total_Output > 0) ? $row_Output['indicator_name'] : '';

                    $table =   get_escalation_table($projid, $site_id, $output_id, $end_date, $member_id);
                    if ($table != '') {
                        $details[] = true;
                        $output_counter++;
                        $output_detail .= '
                            <fieldset class="scheduler-border">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                Output ' . $output_counter . ' : ' . $output . '
                            </legend>
                                    ' . $table . '
                            </fieldset>';
                    }
                }
            }

            if (!empty($details) && in_array(true, $details)) {
                $counter++;
                $detail .= '
                <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    SITE ' . $counter . ' : ' . $site . '
                </legend>
                ' . $output_detail . '
                </fieldset>';
            }
        }
    }

    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1 AND projid = :projid");
    $query_Output->execute(array(":projid" => $projid));
    $total_Output = $query_Output->rowCount();
    if ($total_Output > 0) {
        $site_id = 0;
        $output_counter = 0;
        while ($row_rsOutput = $query_Output->fetch()) {
            $output_id = $row_rsOutput['id'];
            $output = $row_rsOutput['indicator_name'];
            $table =   get_escalation_table($projid, $site_id, $output_id, $end_date, $member_id);
            if ($table != '') {
                $output_counter++;
                $detail .= '
                <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    Output ' . $output_counter . ' : ' . $output . '
                </legend>
                        ' . $table . '
                </fieldset>';
            }
        }
    }

    return $detail;
}

function send_activity_monitoring_mails()
{
    global $db, $mail;
    $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' ");
    $query_rsProjects->execute();
    $totalRows_rsProjects = $query_rsProjects->rowCount();
    if ($totalRows_rsProjects > 0) {
        while ($row_rsProjects = $query_rsProjects->fetch()) {
            $project = $row_rsProjects['projname'];
            $projid = $row_rsProjects['projid'];
            $directorate_id = $row_rsProjects['directorate'];

            $query_rsMembers = $db->prepare("SELECT responsible, role FROM `tbl_projmembers` WHERE projid=:projid  AND stage=:workflow_stage and team_type=4");
            $query_rsMembers->execute(array(":projid" => $projid, ":workflow_stage" => 9));
            $total_rsMembers = $query_rsMembers->rowCount();
            if ($total_rsMembers > 0) {
                while ($row_rsMembers = $query_rsMembers->fetch()) {
                    $member_id = $row_rsMembers['responsible'];
                    $today = date('Y-m-d');
                    $upcoming_reminder_date = date('Y-m-d', strtotime($today . ' + 1 days'));
                    $escalation_date = date('Y-m-d', strtotime($today . ' - 1 days'));
                    // reminder
                    $today_reminders = check_reminder($projid, $today, $member_id);
                    if ($today_reminders != '') {
                        $response = $mail->send_activity_notification($member_id, $projid, $project, $upcoming_reminder_date, '', '', '',  9, 10, 5);
                    }

                    $upcoming_reminders = check_reminder($projid, $upcoming_reminder_date, $member_id);
                    if ($upcoming_reminders != '') {
                        $response = $mail->send_activity_notification($member_id, $projid, $project, $upcoming_reminder_date, '', '', '',  9, 10, 5);
                    }

                    //Escalation
                    $escalations = check_escalation($projid, $escalation_date, $member_id);
                    if ($escalations != '') {
                        $user_id = '';
                        $role_id = $row_rsMembers['role'];
                        if ($role_id == 2) {
                            $sql = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid,u.password FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title  WHERE t.designation=7 AND  directorate=:directorate");
                            $sql->execute(array(":directorate" => $directorate_id));
                            $count_user = $sql->rowCount();
                            $user = $sql->fetch();
                            $user_id = $count_user > 0  ? $user['userid'] : '';
                        } else {
                            $query_rsTeamLeader = $db->prepare("SELECT responsible, role FROM `tbl_projmembers` WHERE projid=:projid  AND stage=:workflow_stage and team_type=4 AND role=2");
                            $query_rsTeamLeader->execute(array(":projid" => $projid, ":workflow_stage" => 9));
                            $total_rsTeamLeader = $query_rsTeamLeader->rowCount();
                            $row_rsTeamLeader = $query_rsTeamLeader->fetch();
                            $user_id = ($total_rsTeamLeader > 0) ? $row_rsTeamLeader['responsible'] : '';
                        }

                        $response = $mail->send_activity_notification($user_id, $projid, $project, $escalation_date, $member_id, '', '',  9, 10, 4);
                    }
                }
            }
        }
    }
}
