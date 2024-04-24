
<?php

$status_array = array(
    'all' => array("status" => ''),
    'complete' => array("status" => 5),
    'on-track' => array("status" => 4),
    'pending' => array("status" => 3),
    'behind-schedule' => array("status" => 11),
    'awaiting-procurement' => array("status" => 1),
    'on-hold' => array("status" => 6),
    'cancelled' => array("status" => 2),
);

$project_distribution_data = $tender_projects = $tender_cost = $budget_data = [];
$allprojectsurl = $prjfyfrom = $prjfyto = $prjsc = $prjward = '';
if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
    // $prjstatus = $_GET['prjstatus'];
    $prjstatus = '';
    $prjfyfrom = $_GET['projfyfrom'];
    $prjfyto = $_GET['projfyto'];
    $prjsc = $_GET['projscounty'];
    $prjward = $_GET['projward'];
    $allprojectsurl = "&projfyfrom=$prjfyfrom&projfyto=$prjfyto&projscounty=$prjsc&projward=$prjward&btn_search=FILTER";
    if (empty($prjfyfrom) && empty($prjsc)) {
        $widget_data = widgets($query = null, $prjsc = null, $prjward = null, $prjstatus, $accesslevel);
    } else {
        $widget_data1 = widgets_filter($prjfyfrom, $prjfyto, $prjsc, $prjward, $prjstatus, $accesslevel);
        $widget_data = $widget_data1['widget_data'];
    }
} else {
    $prjstatus =  (isset($_GET['prjstatus'])) ? $_GET['prjstatus'] : '';
    $widget_data = widgets($query = null, $prjsc = null, $prjward = null, $prjstatus, $accesslevel);
}


function project_cost_vs_department_distribution()
{
    global $db;
    $query_rsDepartments = $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 ORDER BY sector ASC");
    $query_rsDepartments->execute();
    $totalRows_rsDepartments = $query_rsDepartments->rowCount();
    $rate = [];
    if ($totalRows_rsDepartments > 0) {
        while ($row_rsDepartments = $query_rsDepartments->fetch()) {
            $sector_id = $row_rsDepartments['stid'];
            $sector = $row_rsDepartments['sector'];
            $query_rsprojects = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON p.progid= g.progid WHERE p.projstage > 7 AND g.projsector=:sector_id");
            $query_rsprojects->execute(array(":sector_id" => $sector_id));
            $allprojects = $query_rsprojects->rowCount();
            if ($allprojects > 0) {
                while ($row_projects = $query_rsprojects->fetch()) {
                    $projid = $row_projects['projid'];
                    $query_rsPayouts = $db->prepare("SELECT sum(amount) as amount FROM tbl_payments_disbursed WHERE projid=:projid");
                    $query_rsPayouts->execute(array(":projid" => $projid));
                    $row_rsPayouts = $query_rsPayouts->fetch();
                    $amount_paid = !is_null($row_rsPayouts['amount']) ? $row_rsPayouts['amount'] : 0;
                    $amount_paid = $amount_paid;
                }
            }
        }
    }
}



function projfyto($prjfyfrom, $prjfyto)
{
    global $db;
    $projfy = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id >= $prjfyfrom");
    $projfy->execute();
    while ($row = $projfy->fetch()) {
        $selected = $row['id'] == $prjfyto ? "selected" : "";
        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['year'] . '</option>';
    }
}

function prjward($prjsc, $prjward)
{
    global $db;
    $projward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=$prjsc ORDER BY state ASC");
    $projward->execute();
    while ($row = $projward->fetch()) {
        $selected = $row['id'] == $prjward ? "selected" : "";
        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['state'] . '</option>';
    }
}

function widgets_filter($from = null, $to = null, $level1 = null, $level2 = null, $prjstatus, $accesslevel = "")
{
    $widget_array = '';
    if ($from != null) {
        if ($to != null) {
            if ($level1 != null) {
                if ($level2 != null) {
                    // select for only from, to, level 1 and 2
                    $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                    $widget_array = widgets($sql, $level1, $level2, $prjstatus, $accesslevel);
                } else {
                    // select for only from, to and level 1
                    $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                }
            } else {
                // select for only from, to and to
                $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                $widget_array = widgets($sql, $level1 = null, $level2 = null, $prjstatus, $accesslevel);
            }
        } else {
            if ($level1 != null) {
                if ($level2 != null) {
                    // select for only from, level 1 and 2
                    $sql = "p.projfscyear >=" . $from;
                    $widget_array = widgets($sql, $level1, $level2, $prjstatus, $accesslevel);
                } else {
                    // select for only from and level 1
                    $sql = "p.projfscyear >=" . $from;
                    $widget_array = widgets($sql, $level1, $level2 = null, $level3 = null, $prjstatus, $accesslevel);
                }
            } else {
                // select for only from
                $sql = "p.projfscyear >=" . $from;
                $widget_array = widgets($sql, $level1 = null, $level2 = null, $prjstatus, $accesslevel);
            }
        }
    } else {
        if ($level1 != null) {
            if ($level2 != null) {
                // select for only level 1 and 2
                $widget_array = widgets($sql = null, $level1, $level2, $level3 = null, $prjstatus);
            } else {
                // select for only level 1
                $widget_array = widgets($sql = null, $level1, $level2 = null, $prjstatus, $accesslevel);
            }
        }
    }

    return array(
        "widget_data" => $widget_array,
    );
}

function widgets($query = null, $level1 = null, $level2 = null, $projstatus = null, $accesslevel = null)
{
    global $status_array, $db;
    $widgets = array();
    foreach ($status_array as $key => $project_status) {
        $status = $project_status['status'];
        $stmt = '';
        if ($status != '') {
            $stmt = "projstatus =" . $status;
        }

        $where = $stmt != "" ? $accesslevel . " AND " . $stmt : $accesslevel;
        $where = $query != null ? $where . " AND " . $query : $where;

        $sql = "SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage > 0 and p.deleted = '0' " . $where;
        $query_rsprojects = $db->prepare($sql);
        $query_rsprojects->execute();
        $row_projects = $query_rsprojects->fetch();
        $allprojects = $query_rsprojects->rowCount();


        if ($allprojects > 0) {
            if ($level1 != null) {
                $count_projects = 0;
                do {
                    $projcommunity = explode(",", $row_projects['projcommunity']);
                    $projlga = explode(",", $row_projects['projlga']);

                    if ($level2 != null) {
                        if (in_array($level2, $projlga)) {
                            $count_projects += 1;
                        }
                    } else {
                        if (in_array($level1, $projcommunity)) {
                            $count_projects += 1;
                        }
                    }
                } while ($row_projects = $query_rsprojects->fetch());
                $widgets[$key] = $count_projects;
            } else {
                $widgets[$key] = $allprojects;
            }
        } else {
            $widgets[$key] = 0;
        }
    }
    return $widgets;
}


function fund_sources()
{
    global $db, $strategic_plan_start_year, $total_strategic_plan_years;
    $start_year = $strategic_plan_start_year;
    $query_rsFunding_type =  $db->prepare("SELECT id, type FROM tbl_funding_type");
    $query_rsFunding_type->execute();
    $totalRows_rsFunding_type = $query_rsFunding_type->rowCount();
    $data = [];
    if ($totalRows_rsFunding_type > 0) {
        while ($row_rsFunding_type = $query_rsFunding_type->fetch()) {
            $organization = $row_rsFunding_type['type'];
            $financier_id = $row_rsFunding_type['id'];
            $amount_contributed = [];
            for ($i = 0; $i < $total_strategic_plan_years; $i++) {
                $query_finyearamtgrants = $db->prepare("SELECT sum(amount) AS totalamt FROM tbl_financiers f  INNER JOIN tbl_financier_type t on f.type = t.id  INNER JOIN tbl_funding_type s on s.category = t.id INNER JOIN tbl_funds p on p.funder = f.id WHERE t.id = :financier_id AND p.financial_year=:financial_year_id");
                $query_finyearamtgrants->execute(array(":financier_id" => $financier_id, ":financial_year_id" => $start_year));
                $row_finyearamtgrants = $query_finyearamtgrants->fetch();
                $total_amount = !is_null($row_finyearamtgrants["totalamt"]) ? $row_finyearamtgrants["totalamt"] : 0;
                array_push($amount_contributed, $total_amount);
                $start_year++;
            }
            $information = array("name" => (string)$organization, "data" => $amount_contributed);
            array_push($data, $information);
        }
    }
    return json_encode($data);
}

function budget_vs_expenditure_per_year()
{
    global $db, $strategic_plan_start_year, $total_strategic_plan_years;
    $start_year = $strategic_plan_start_year;
    $totalannualbdg =  $totalannualexp = [];
    for ($i = 0; $i < $total_strategic_plan_years; $i++) {
        $end_year = $start_year + 1;
        $start_date = date("Y-m-d", strtotime($start_year . "07-01"));
        $end_date = date("Y-m-d", strtotime($end_year . "06-30"));
        $query_crfinyearalloc = $db->prepare("SELECT sum(b.budget) as totalamt FROM tbl_programs_based_budget b inner join tbl_programs g on g.progid=b.progid WHERE g.program_type=1 AND b.finyear=:finyear");
        $query_crfinyearalloc->execute(array(":finyear" => $start_year));
        $row_crfinyearalloc = $query_crfinyearalloc->fetch();
        $totalannualbudget =  !is_null($row_crfinyearalloc["totalamt"]) ? $row_crfinyearalloc["totalamt"] : 0;

        $query_annualexpenditure = $db->prepare("SELECT sum(amount_requested) AS totalexpend FROM tbl_payments_request r inner join tbl_payments_disbursed d on d.request_id=r.request_id inner join tbl_projects p on p.projid=r.projid inner join tbl_programs g on g.progid=p.progid WHERE g.program_type=1 AND r.status=3 AND r.date_requested >=:start_date AND r.date_requested <=:end_date");
        $query_annualexpenditure->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
        $row_annualexpenditure = $query_annualexpenditure->fetch();
        $totalannualexpenditure = !is_null($row_annualexpenditure["totalexpend"]) ? $row_annualexpenditure["totalexpend"] : 0;

        $query_annual_contractor_expenditure = $db->prepare("SELECT sum(requested_amount) AS totalexpend FROM tbl_contractor_payment_requests r inner join tbl_payments_disbursed d on d.request_id=r.request_id inner join tbl_projects p on p.projid=r.projid inner join tbl_programs g on g.progid=p.progid WHERE g.program_type=1 AND r.status=3 AND r.created_at >=:start_date AND r.created_at <= :end_date");
        $query_annual_contractor_expenditure->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
        $row_annual_contractor_expenditure = $query_annual_contractor_expenditure->fetch();
        $total_annual_contractor_expenditure = !is_null($row_annual_contractor_expenditure["totalexpend"]) ? $row_annual_contractor_expenditure["totalexpend"] : 0;
        $totalannualexpenditure = $totalannualexpenditure + $total_annual_contractor_expenditure;
        array_push($totalannualbdg, (float)$totalannualbudget);
        array_push($totalannualexp, (float)$totalannualexpenditure);
        $start_year++;
    }
    return array("totalannualbdg" => $totalannualbdg, "totalannualexp" => $totalannualexp);
}

function projects_vs_tender_category()
{
    global $db;
    $query_tender_category = $db->prepare("SELECT * FROM tbl_tender_category WHERE status = 1 ");
    $query_tender_category->execute();
    $count_tender_category = $query_tender_category->rowCount();

    if ($count_tender_category > 0) {
        while ($rows_tender_category = $query_tender_category->fetch()) {
            $tender_catid = $rows_tender_category['id'];
            $t = $rows_tender_category['category'];
            $sql = "SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage >5 and p.projcategory='2' and p.deleted = '0' ";
            $query_rsprojects = $db->prepare($sql);
            $query_rsprojects->execute();
            $allprojects = $query_rsprojects->rowCount();

            if ($allprojects > 0) {
                while ($row_projects = $query_rsprojects->fetch()) {
                    $projid = $row_projects['projid'];
                    $query_tender_projects = $db->prepare("SELECT projid, tt.id FROM tbl_tenderdetails t INNER JOIN tbl_tender_category tt ON t.tendercat=tt.id  INNER JOIN tbl_tender_type tc ON t.tendertype=tc.id AND tt.id='$tender_catid' AND projid='$projid'");
                    $query_tender_projects->execute();
                    $count_tender_projects = $query_tender_projects->rowCount();
                    $rows_tender_projects = $query_tender_projects->fetch();
                }
            }
        }
    }
    return;
}

function projects_cost_vs_tender_category()
{
    global $db;
    $data_no_arr = "[['Tenders', 'Project Amount'],";
    $query_tender_category = $db->prepare("SELECT * FROM tbl_tender_category WHERE status = 1");
    $query_tender_category->execute();
    $count_tender_category = $query_tender_category->rowCount();
    $rows_tender_category = $query_tender_category->fetch();

    if ($count_tender_category > 0) {
        while ($rows_tender_category = $query_tender_category->fetch()) {
            $tender_catid = $rows_tender_category['id'];
            $sql = "SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage >5 and p.projcategory='2' and p.deleted = '0' ";
            $query_rsprojects = $db->prepare($sql);
            $query_rsprojects->execute();
            $row_projects = $query_rsprojects->fetch();
            $allprojects = $query_rsprojects->rowCount();

            if ($allprojects > 0) {
                while ($row_projects = $query_rsprojects->fetch()) {
                    $projcommunity = explode(",", $row_projects['projcommunity']);
                    $projlga = explode(",", $row_projects['projlga']);
                    $projid = $row_projects['projid'];
                    $query_tender_projects = $db->prepare("SELECT SUM((unit_cost * units_no)) as totalamt FROM tbl_projects p INNER JOIN tbl_tenderdetails t  ON p.projtender=t.td_id INNER JOIN tbl_project_tender_details d  ON p.projid=d.projid INNER JOIN tbl_tender_category tt ON t.tendercat=tt.id INNER JOIN tbl_tender_type tc ON t.tendertype=tc.id WHERE  tt.id='$tender_catid' AND p.projid='$projid'");
                    $query_tender_projects->execute();
                    $count_tender_projects = $query_tender_projects->rowCount();
                    $rows_tender_projects = $query_tender_projects->fetch();
                    $amt = $rows_tender_projects['totalamt'];
                }
            }
        }
    }
    return $data_no_arr;
}
