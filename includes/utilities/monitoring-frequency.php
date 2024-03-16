<?php
include '../controller.php';

function index($duration, $start_year)
{
    $f_start = '07-01';
    $f_end = '06-30';
    $startYears =  [];
    for ($i = 0; $i < $duration; $i++) {
        $m_start = $start_year . '-' . $f_start;
        $f_year_end = $start_year + 1 . '-' . $f_end;
        $startYears[] =  [$m_start, $f_year_end];
        $start_year++;
    }
    $annually = [];
    for ($i = 0; $i < count($startYears); $i++) {
        $startFinancial = $startYears[$i][0];
        $endFinancial = $startYears[$i][1];
        $startFinancialMidPoint = strtotime('+6 months -1 day', strtotime($startFinancial));
        $date = date('Y-m-d', $startFinancialMidPoint);
        $endFinancialMidPoint = strtotime('-6 months +2 day', strtotime($endFinancial));
        $datetwo = date('Y-m-d', $endFinancialMidPoint);
        $annually[] = [[$startFinancial, $date], [$datetwo, $endFinancial]];
    }

    $quarterly = [];
    for ($i = 0; $i < count($annually); $i++) {
        for ($t = 0; $t < count($annually[$i]); $t++) {
            $startFinancial = $annually[$i][$t][0];
            $endFinancial = $annually[$i][$t][1];
            $startFinancialMidPoint = strtotime('+3 months -1 day', strtotime($startFinancial));
            $date = date('Y-m-d', $startFinancialMidPoint);
            $endFinancialMidPoint = strtotime('-3 months +2 day', strtotime($endFinancial));
            $datetwo = date('Y-m-d', $endFinancialMidPoint);
            $quarterly[] = [[$startFinancial, $date], [$datetwo, $endFinancial]];
        }
    }


    $monthly = [];
    for ($i = 0; $i < count($quarterly); $i++) {
        for ($t = 0; $t < count($quarterly[$i]); $t++) {
            $startFinancial = $quarterly[$i][$t][0];
            $endFinancial = $quarterly[$i][1][1];
            while ($startFinancial <= $endFinancial) {
                $startFinancialMidPoint = strtotime('+1 month -1 day', strtotime($startFinancial));
                $date = date('Y-m-d', $startFinancialMidPoint);
                $monthly[] = [$startFinancial, $date];
                $startFinancial = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            }
            break;
        }
    }

    return array("startYears" => $startYears, "annually" => $annually, "quarterly" => $quarterly, "monthly" => $monthly);
}

function store_monitoring_frequency($projid, $site_id, $output_id, $task_id, $subtask_id, $start_date, $end_date)
{
    global $db;
    $sql = $db->prepare("INSERT INTO tbl_project_target_breakdown (projid, output_id, site_id, task_id, subtask_id,start_date,end_date,created_at) VALUES (:projid, :output_id, :site_id, :task_id, :subtask_id,:start_date,:end_date, :created_at)");
    $results = $sql->execute(array(':projid' => $projid, ":output_id" => $output_id, ":site_id" => $site_id, ":task_id" => $task_id, ":subtask_id" => $subtask_id, ':start_date' => $start_date, ':end_date' => $end_date, ':created_at' => date('Y-m-d')));
    return $results;
}

function getStartAndEndDate($week, $year)
{
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}

function filter_head($contractor_start, $contractor_end, $start_date, $end_date, $task_start_date, $task_end_date)
{
    $response = false;
    if (
        ($contractor_start >= $start_date && $contractor_start <= $end_date) ||
        ($contractor_end >= $start_date && $contractor_end <= $end_date) ||
        ($contractor_start <= $start_date && $contractor_end >= $start_date && $contractor_end >= $end_date)
    ) {
        if (
            ($task_start_date >= $start_date && $task_start_date <= $end_date) ||
            ($task_end_date >= $start_date && $task_end_date <= $end_date) ||
            ($task_start_date <= $start_date && $task_end_date >= $start_date && $task_end_date >= $end_date)
        ) {
            $response = true;
        }
    }
    return $response;
}

function get_annual_table($project_years, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id)
{
    $response = false;
    for ($i = 0; $i < count($project_years); $i++) {
        $start_date = $project_years[$i][0];
        $end_date = $project_years[$i][1];
        $response = filter_head($contractor_start, $contractor_end, $start_date, $end_date, $task_start_date, $task_end_date);
        if ($response) {
            $response = store_monitoring_frequency($projid, $site_id, $output_id, $task_id, $subtask_id, $start_date, $end_date);
        }
    }
    return $response;
}

function get_semiannual_table($semi_annual_dates, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id)
{
    $response = false;
    for ($i = 0; $i < count($semi_annual_dates); $i++) {
        for ($t = 0; $t < count($semi_annual_dates[$i]); $t++) {
            $start_date = $semi_annual_dates[$i][$t][0];
            $end_date = $semi_annual_dates[$i][$t][1];
            $response = filter_head($contractor_start, $contractor_end, $start_date, $end_date, $task_start_date, $task_end_date);
            if ($response) {
                $response = store_monitoring_frequency($projid, $site_id, $output_id, $task_id, $subtask_id, $start_date, $end_date);
            }
        }
    }
    return $response;
}

function get_quarterly_table($quarterly, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id)
{
    $response = false;
    for ($i = 0; $i < count($quarterly); $i++) {
        for ($t = 0; $t < count($quarterly[$i]); $t++) {
            $start_date = $quarterly[$i][$t][0];
            $end_date = $quarterly[$i][$t][1];
            $response = filter_head($contractor_start, $contractor_end, $start_date, $end_date, $task_start_date, $task_end_date);
            if ($response) {
                $response = store_monitoring_frequency($projid, $site_id, $output_id, $task_id, $subtask_id, $start_date, $end_date);
            }
        }
    }

    return $response;
}

function get_monthly_table($monthly, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id)
{
    $response = false;
    for ($i = 0; $i < count($monthly); $i++) {
        $start_date = $monthly[$i][0];
        $end_date = $monthly[$i][1];
        $response = filter_head($contractor_start, $contractor_end, $start_date, $end_date, $task_start_date, $task_end_date);
        if ($response) {
            $response = store_monitoring_frequency($projid, $site_id, $output_id, $task_id, $subtask_id, $start_date, $end_date);
        }
    }
    return $response;
}

function get_weekly_table($project_start_date, $project_end_date, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id)
{
    $start_year = date('Y', strtotime($project_start_date));
    $end_year = date('Y', strtotime($project_end_date));
    $end_month = date('M', strtotime($project_end_date));
    $end_year = ($end_month >= 7 && $end_month <= 12) ? $end_year + 1 : $end_year;
    $duration = ($end_year - $start_year)  + 1;
    $head_year = $start_year;
    for ($j = 0; $j < $duration; $j++) {
        for ($i = 1; $i < 53; $i++) {
            $week_array = getStartAndEndDate($i, $head_year);
            $start_date =  date('Y-m-d', strtotime($week_array['week_start']));
            $end_date =  date('Y-m-d', strtotime($week_array['week_end']));
            if ($project_start_date < $end_date && $project_end_date >= $start_date) {
                $response = filter_head($contractor_start, $contractor_end, $start_date, $end_date, $task_start_date, $task_end_date);
                if ($response) {
                    $response = store_monitoring_frequency($projid, $site_id, $output_id, $task_id, $subtask_id, $start_date, $end_date);
                }
            }
        }
        $head_year++;
    }
    return $response;
}

function get_daily_table($project_start_date, $project_end_date, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id)
{
    $response = false;
    $start_date = $project_start_date;
    $end_date = $project_end_date;
    if ($contractor_start != '' && $contractor_end != '') {
        $start_date = $contractor_start;
        $end_date = $contractor_end;
    }

    if ($task_start_date != '' && $task_end_date != '') {
        $start_date = $task_start_date;
        $end_date = $task_end_date;
    }

    while ($start_date <= $end_date) {
        $date = date('Y-m-d', strtotime($start_date));
        $response = store_monitoring_frequency($projid, $site_id, $output_id, $task_id, $subtask_id, $date, $date);
    }
    return $response;
}

function get_duration($min_date, $max_date)
{
    $currentYear = date('Y', strtotime($min_date));
    $month = date('m', strtotime($min_date));
    $start_year = ($month >= 7 && $month <= 12) ? $currentYear : $currentYear - 1;

    $currentYear = date('Y', strtotime($max_date));
    $month = date('m', strtotime($max_date));
    $end_year = ($month >= 7 && $month <= 12) ? $currentYear : $currentYear - 1;
    $duration = ($end_year - $start_year) + 1;
    return array("duration" => $duration, "start_year" => $start_year);
}

function create_internal_frequency()
{
    global $db;
    $projid = $_GET['projid'];
    $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
    $query_rsProjects->execute(array(":projid" => $projid));
    $row_rsProjects = $query_rsProjects->fetch();
    $totalRows_rsProjects = $query_rsProjects->rowCount();
    if ($totalRows_rsProjects > 0) {
        $min_date = $row_rsProjects['projstartdate'];
        $max_date = $row_rsProjects['projenddate'];
        $max_date = $row_rsProjects['projenddate'];
        $frequency = $row_rsProjects['monitoring_frequency'];
        $projcategory =  $row_rsProjects['projcategory'];
        $date_details = get_duration($min_date, $max_date);
        $details = index($date_details['duration'], $date_details['start_year']);
        $startYears = $details['startYears'];
        $annually = $details['annually'];
        $quarterly = $details['quarterly'];
        $monthly = $details['monthly'];

        $contractor_start = $min_date;
        $contractor_end = $max_date;
        if ($projcategory == 2) {
            $query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid");
            $query_rsTender->execute(array(":projid" => $projid));
            $row_rsTender = $query_rsTender->fetch();
            $totalRows_rsTender = $query_rsTender->rowCount();
            $contractor_start = $end_date = '';
            if ($totalRows_rsTender > 0) {
                $contractor_start = $row_rsTender['startdate'];
                $contractor_end = $row_rsTender['enddate'];
            }
        }


        $query_rsWorkBreakdown = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid=:projid");
        $query_rsWorkBreakdown->execute(array(':projid' => $projid));
        $rows_rsWorkBreakdown = $query_rsWorkBreakdown->rowCount();

        if ($rows_rsWorkBreakdown) {
            while ($row_rsWorkBreakdown = $query_rsWorkBreakdown->fetch()) {
                $site_id = $row_rsWorkBreakdown['site_id'];
                $output_id = $row_rsWorkBreakdown['output_id'];
                $task_id = $row_rsWorkBreakdown['task_id'];
                $subtask_id = $row_rsWorkBreakdown['subtask_id'];
                $task_start_date = $row_rsWorkBreakdown['subtask_id'];
                $task_end_date = $row_rsWorkBreakdown['subtask_id'];
                if ($frequency == 6) {
                    $response = get_annual_table($startYears, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id);
                } elseif ($frequency == 5) {
                    $response = get_semiannual_table($annually, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id);
                } else if ($frequency == 4) {
                    $response = get_quarterly_table($quarterly, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id);
                } else if ($frequency == 3) {
                    $response = get_monthly_table($monthly, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id);
                } else if ($frequency == 2) {
                    $response = get_weekly_table($min_date, $max_date, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id);
                } else if ($frequency == 1) {
                    $response  = get_daily_table($min_date, $max_date, $contractor_start, $contractor_end, $task_start_date, $task_end_date, $projid, $site_id, $output_id, $task_id, $subtask_id);
                }
            }
        }
    }
}