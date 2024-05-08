<?php
try {
    require('includes/head.php');
    if ($permission) {

        $project_distribution_data = $tender_projects = $tender_cost = $budget_data = [];
        $allprojectsurl = $prjfyfrom = $prjfyto = $prjsc = $prjward = '';
        $financial_year_from = $financial_year_to = $level_one_id = $level_two_id = '';
        function get_access_level()
        {
            global $user_designation, $user_department, $user_section, $user_directorate;
            $access_level = "";

            if (($user_designation < 5)) {
                $access_level = "";
            } elseif ($user_designation == 5) {
                $access_level = " AND g.projsector=$user_department";
            } elseif ($user_designation == 6) {
                $access_level = " AND g.projsector=$user_department AND g.projdept=$user_section";
            } elseif ($user_designation > 6) {
                $access_level = " AND g.projsector=$user_department AND g.projdept=$user_section AND g.directorate=$user_directorate";
            }
            return $access_level;
        }
        $access_level = get_access_level();

        function departments()
        {
            global $db;
            $departments = [];
            $query_rsDepartments = $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 ORDER BY sector ASC");
            $query_rsDepartments->execute();
            $totalRows_rsDepartments = $query_rsDepartments->rowCount();

            if ($totalRows_rsDepartments > 0) {
                while ($row_rsDepartments = $query_rsDepartments->fetch()) {
                    $departments[] = $row_rsDepartments['sector'];
                }
            }
            return json_encode($departments);
        }

        function get_level_one($level_one_id)
        {
            global $db;
            $level_ones = '';
            $id = [];
            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
            $query_rsComm->execute();
            $totalRows_rsComm = $query_rsComm->rowCount();
            if ($totalRows_rsComm > 0) {
                while ($row_rsComm = $query_rsComm->fetch()) {
                    $comm = $row_rsComm['id'];
                    $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm ");
                    $query_ward->execute(array(":comm" => $comm));
                    while ($row = $query_ward->fetch()) {
                        $projlga = $row['id'];
                        $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                        $query_rsLocations->execute(array(":id" => $projlga));
                        $total_locations = $query_rsLocations->rowCount();
                        if ($total_locations > 0) {
                            $selected_sb = $row_rsComm['id'] == $level_one_id ? "selected" : "";
                            if (!in_array($comm, $id)) {
                                $level_ones .= '<option value="' . $row_rsComm['id'] . '" ' . $selected_sb . '>' . $row_rsComm['state'] . '</option>';
                            }
                            $id[] = $row_rsComm['id'];
                        }
                    }
                }
            }
            return $level_ones;
        }

        function project_cost_vs_department_distribution($where)
        {
            global $db;
            $query_rsDepartments = $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 ORDER BY sector ASC");
            $query_rsDepartments->execute();
            $totalRows_rsDepartments = $query_rsDepartments->rowCount();
            $rate = [];
            $budget_data = "[";
            if ($totalRows_rsDepartments > 0) {
                while ($row_rsDepartments = $query_rsDepartments->fetch()) {
                    $sector_id = $row_rsDepartments['stid'];
                    $sector = $row_rsDepartments['sector'];
                    $query_rsprojects = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON p.progid= g.progid WHERE p.projstage > 7 AND g.projsector=:sector_id $where");
                    $query_rsprojects->execute(array(":sector_id" => $sector_id));
                    $allprojects = $query_rsprojects->rowCount();
                    $k = $p = 0;

                    if ($allprojects > 0) {
                        while ($row_projects = $query_rsprojects->fetch()) {
                            $projid = $row_projects['projid'];
                            $query_rsApprovedCost = $db->prepare("SELECT sum(amount) as amount FROM tbl_project_approved_yearly_budget WHERE projid=:projid");
                            $query_rsApprovedCost->execute(array(":projid" => $projid));
                            $row_rsApprovedCost = $query_rsApprovedCost->fetch();

                            $query_rsPayouts = $db->prepare("SELECT sum(amount) as amount FROM tbl_payments_disbursed WHERE projid=:projid");
                            $query_rsPayouts->execute(array(":projid" => $projid));
                            $row_rsPayouts = $query_rsPayouts->fetch();
                            $k += !is_null($row_rsPayouts['amount']) ? $row_rsPayouts['amount'] : 0;
                            $p += !is_null($row_rsApprovedCost['amount']) ? $row_rsApprovedCost['amount'] : 0;
                        }
                    }

                    $rate = ($k != 0 && $p != 0) ? round(($k / $p) * 100, 2) : 0;

                    $budget_data .= "
                        {
                            x: '$sector',
                            y: '$rate',
                            goals: [
                            {
                                name: 'Expected',
                                value: '100',
                                strokeHeight: 5,
                                strokeColor: '#775DD0'
                            }
                            ]
                        },";
                }
            }
            $budget_data .= "]";
            return $budget_data;
        }

        function projects_cost_vs_tender_category($where)
        {
            global $db;
            $data_no_arr = "[['Tenders', 'Project Amount'],";
            $query_tender_category = $db->prepare("SELECT * FROM tbl_tender_category WHERE status = 1");
            $query_tender_category->execute();
            $count_tender_category = $query_tender_category->rowCount();

            if ($count_tender_category > 0) {
                while ($rows_tender_category = $query_tender_category->fetch()) {
                    $tender_catid = $rows_tender_category['id'];
                    $t = $rows_tender_category['category'];
                    $sql = "SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage >5 and p.projcategory='2' and p.deleted = '0' $where ";
                    $query_rsprojects = $db->prepare($sql);
                    $query_rsprojects->execute();
                    $allprojects = $query_rsprojects->rowCount();

                    if ($allprojects > 0) {
                        $n = 0;
                        while ($row_projects = $query_rsprojects->fetch()) {
                            $projid = $row_projects['projid'];
                            $query_tender_projects = $db->prepare("SELECT SUM((unit_cost * units_no)) as totalamt FROM tbl_projects p  INNER JOIN tbl_tenderdetails t  ON p.projtender=t.td_id INNER JOIN tbl_project_tender_details d  ON p.projid=d.projid INNER JOIN tbl_tender_category tt ON t.tendercat=tt.id INNER JOIN tbl_tender_type tc ON t.tendertype=tc.id WHERE  tt.id=:tender_catid AND p.projid=:projid");
                            $query_tender_projects->execute(array(":tender_catid" => $tender_catid, ":projid" => $projid));
                            $rows_tender_projects = $query_tender_projects->fetch();
                            $n += !is_null($rows_tender_projects['totalamt']) ? $rows_tender_projects['totalamt'] : 0;
                        }
                        $data_no_arr .= "['" . $t . "', " . $n . "],";
                    } else {
                        $n = 0;
                        $data_no_arr .= "['" . $t . "', " . $n . "],";
                    }
                }
            }
            $data_no_arr .= ']';
            return $data_no_arr;
        }

        function projects_vs_tender_category($where)
        {
            global $db;
            $data_no_arr = "[['Tenders', 'Project Number'],";
            $query_tender_category = $db->prepare("SELECT * FROM tbl_tender_category WHERE status = 1 ");
            $query_tender_category->execute();
            $count_tender_category = $query_tender_category->rowCount();

            if ($count_tender_category > 0) {
                while ($rows_tender_category = $query_tender_category->fetch()) {
                    $tender_catid = $rows_tender_category['id'];
                    $t = $rows_tender_category['category'];
                    $sql = "SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstage >5 and p.projcategory='2' and p.deleted = '0' $where ";
                    $query_rsprojects = $db->prepare($sql);
                    $query_rsprojects->execute();
                    $allprojects = $query_rsprojects->rowCount();
                    if ($allprojects > 0) {
                        $n = 0;
                        while ($row_projects = $query_rsprojects->fetch()) {
                            $projid = $row_projects['projid'];
                            $query_tender_projects = $db->prepare("SELECT projid, tt.id FROM tbl_tenderdetails t INNER JOIN tbl_tender_category tt ON t.tendercat=tt.id  INNER JOIN tbl_tender_type tc ON t.tendertype=tc.id AND tt.id=:tender_catid AND projid=:projid");
                            $query_tender_projects->execute(array(":tender_catid" => $tender_catid, ":projid" => $projid));
                            $count_tender_projects = $query_tender_projects->rowCount();
                            $n += ($count_tender_projects > 0) ? 1 : 0;
                        }
                        $data_no_arr .= "['" . $t . "', " . $n . "],";
                    } else {
                        $n = 0;
                        $data_no_arr .= "['" . $t . "', " . $n . "],";
                    }
                }
            }
            $data_no_arr .= ']';
            return $data_no_arr;
        }

        function get_risk_levels()
        {
            global $db;
            $sql = $db->prepare("SELECT * FROM tbl_issue_status WHERE id<>7");
            $sql->execute();
            $row = $sql->fetch();
            $rows_count = $sql->rowCount();

            $data_no_arr = "[['Tenders', 'Project Number'],";
            if ($rows_count > 0) {
                $n = 0;
                while ($row = $sql->fetch()) {
                    $status_id = $row['id'];
                    $t = $row['status'];
                    $query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues  WHERE status = :status");
                    $query_rsProjissues->execute(array(":status" => $status_id));
                    $totalRows_rsProjissues = $query_rsProjissues->rowCount();
                    $n += ($totalRows_rsProjissues > 0) ? 1 : 0;
                }
                $data_no_arr .= "['" . $t . "', " . $n . "],";
            }
            $data_no_arr .= ']';
            return $data_no_arr;
        }

        function get_issues_status()
        {
            global $db, $strategic_plan_start_year, $total_strategic_plan_years;
            $start_year = $strategic_plan_start_year;
            $recorded = $pending = $resolved = '';
            for ($i = 0; $i < $total_strategic_plan_years; $i++) {
                $end_year = $start_year + 1;
                $start_date = date("Y-m-d", strtotime($start_year . "-07-01"));
                $end_date = date("Y-m-d", strtotime($end_year . "-06-30"));

                // recorded
                $query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues  WHERE date_created >= :start_date AND date_created <= :end_date ");
                $query_rsProjissues->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
                $totalRows_rsProjissues = $query_rsProjissues->rowCount();
                $recorded .= "
                {
                    y: $totalRows_rsProjissues,
                    key: 'recorded',
                    year: $start_year,
                },";

                // pending
                $query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues  WHERE status <> 7 AND date_created >= :start_date AND date_created <= :end_date");
                $query_rsProjissues->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
                $totalRows_rsProjissues = $query_rsProjissues->rowCount();
                $pending .= "
                {
                    y: $totalRows_rsProjissues,
                    key: 'pending',
                    year: $start_year,
                },";

                // resolved
                $query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues  WHERE status = 7 AND date_created >= :start_date AND date_created <= :end_date");
                $query_rsProjissues->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
                $totalRows_rsProjissues = $query_rsProjissues->rowCount();
                $resolved .= "
                {
                    y: $totalRows_rsProjissues,
                    key: 'resolved',
                    year: $start_year,
                },";

                $start_year++;
            }

            $series_data = "[
                {
                    name:'Recorded',
                    color: '#0096FF',
                    data:[
                        $recorded
                    ],
                },
                {
                    name:'Resolved',
                    color: '#00FF00',
                    data:[
                        $resolved
                    ],
                }, {
                    name:'Pending',
                    color: '#FFA500',
                    data:[
                        $pending
                    ],
                }]";
            return $series_data;
        }

        $query_stratplan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1");
        $query_stratplan->execute();
        $row_stratplan = $query_stratplan->fetch();
        $totalRows_stratplan = $query_stratplan->rowCount();

        $stplan = $plan = $vision = $mission = $noyears = $styear =  $total_strategic_plan_years = $strategic_plan_start_year = 0;
        $financial_year_details = "";
        if ($totalRows_stratplan > 0) {
            $stplan = $row_stratplan["id"];
            $plan = $row_stratplan["plan"];
            $vision = $row_stratplan["vision"];
            $mission = $row_stratplan["mission"];
            $noyears = $row_stratplan["years"];
            $styear = $row_stratplan["starting_year"];
            $total_strategic_plan_years = $row_stratplan["years"];
            $strategic_plan_start_year = $row_stratplan["starting_year"];
        }

        $current_date = date("Y-m-d");
        $month =  date('m');
        $current_year =  ($month  < 7)  ? date("Y") - 1 : date("Y");


        $nextyear = $current_year + 1;
        $last_year = $current_year - 1;
        $currfinyear = $current_year . "/" . $nextyear;
        $prevfinyear = $last_year . "/" . $current_year;


        function current_financial_year($currentfinyear)
        {
            global $db;
            $query_crfinyear =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$currentfinyear'");
            $query_crfinyear->execute();
            $row_crfinyear = $query_crfinyear->fetch();
            $currentfinyearid = $row_crfinyear["id"];

            $query_crfinyear_budget = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projfscyear = $currentfinyearid ");
            $query_crfinyear_budget->execute();
            $row_crfinyear_budget = $query_crfinyear_budget->fetch();
            $budget_total = $row_crfinyear_budget["totalbudget"];
            $budget_total = $budget_total > 0 ?  $budget_total : 0;
            $totalcrfybudget = number_format($budget_total, 2);

            $query_crfinyearalloc = $db->prepare("SELECT sum(b.budget) as totalamt FROM tbl_programs_based_budget b inner join tbl_programs g on g.progid=b.progid WHERE b.finyear=$currentfinyear ");
            $query_crfinyearalloc->execute();
            $row_crfinyearalloc = $query_crfinyearalloc->fetch();
            $totalcrfinyearalloc = $row_crfinyearalloc["totalamt"];
            $totalcrfinyearalloc = $row_crfinyearalloc && $totalcrfinyearalloc > 0 ?  $totalcrfinyearalloc : 0;
            $totalcrfinyearallocation = number_format($totalcrfinyearalloc, 2);

            $crfinyearrate = 0;
            if ($row_crfinyearalloc) {
                $crfinyearrate = $totalcrfinyearalloc > 0 &&  $budget_total ?  round(($totalcrfinyearalloc / $budget_total) * 100, 2) : 0;
            }
            return array("totalcrfybudget" => $totalcrfybudget, "crfinyearrate" => $crfinyearrate, "totalcrfinyearamt" => $totalcrfinyearallocation, "totalcrfinyearallocation" => $totalcrfinyearalloc);
        }

        // previous financial year detail
        function previous_financial_year($previousfinyear)
        {
            global $db;

            $query_crfinyear =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$previousfinyear'");
            $query_crfinyear->execute();
            $row_crfinyear = $query_crfinyear->fetch();
            $previousfinyearid = $row_crfinyear["id"];

            $query_pvfinyearbudget = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projfscyear = $previousfinyearid");
            $query_pvfinyearbudget->execute();
            $row_pvfinyearbudget = $query_pvfinyearbudget->fetch();
            $total_budget = $row_pvfinyearbudget["totalbudget"];
            $total_budget = $total_budget > 0 ?  $total_budget : 0;
            $totalpvfybudget = number_format($total_budget, 2);

            $query_pvfinyearalloc = $db->prepare("SELECT sum(b.budget) as totalamt FROM tbl_programs_based_budget b inner join tbl_programs g on g.progid=b.progid WHERE b.finyear=$previousfinyear");
            $query_pvfinyearalloc->execute();
            $row_pvfinyearalloc = $query_pvfinyearalloc->fetch();
            $totalpvfinyearalloc = $row_pvfinyearalloc["totalamt"];
            $totalpvfinyearalloc = $totalpvfinyearalloc > 0 ?  $totalpvfinyearalloc : 0;

            $pvfinyearrate = 0;

            $pvfinyearrate = $totalpvfinyearalloc > 0 &&  $total_budget > 0 ? round(($totalpvfinyearalloc / $total_budget) * 100, 2) : 0;
            $totalpvfinyearallocation = number_format($totalpvfinyearalloc, 2);

            return array("totalpvfinyearalloc" => $totalpvfinyearallocation, "pvfinyearrate" => $pvfinyearrate, "totalpvfybudget" => $totalpvfybudget, 'totalpvfinyearallocation' => $totalpvfinyearalloc);
        }

        function fund_sources()
        {
            global $db, $strategic_plan_start_year, $total_strategic_plan_years;
            $query_rsFunding_type =  $db->prepare("SELECT id, type FROM tbl_funding_type ");
            $query_rsFunding_type->execute();
            $totalRows_rsFunding_type = $query_rsFunding_type->rowCount();
            $data = [];
            if ($totalRows_rsFunding_type > 0) {
                while ($row_rsFunding_type = $query_rsFunding_type->fetch()) {
                    $organization = $row_rsFunding_type['type'];
                    $financier_id = $row_rsFunding_type['id'];
                    $amount_contributed = [];
                    $start_year = $strategic_plan_start_year;
                    for ($i = 0; $i < $total_strategic_plan_years; $i++) {
                        $query_rsFinancial_Year =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr=:year");
                        $query_rsFinancial_Year->execute(array(":year" => $start_year));
                        $row_rsFinancial_Year = $query_rsFinancial_Year->fetch();
                        $totalRows_rsFinancial_Year = $query_rsFinancial_Year->rowCount();
                        if ($totalRows_rsFinancial_Year > 0) {
                            $fsc_year = $row_rsFinancial_Year['id'];
                            $query_finyearamtgrants = $db->prepare("SELECT sum(amount) AS totalamt FROM tbl_financiers f  INNER JOIN tbl_financier_type t on f.type = t.id  INNER JOIN tbl_funding_type s on s.category = t.id INNER JOIN tbl_funds p on p.funder = f.id WHERE t.id = :financier_id AND p.financial_year=:financial_year_id");
                            $query_finyearamtgrants->execute(array(":financier_id" => $financier_id, ":financial_year_id" => $fsc_year));
                            $row_finyearamtgrants = $query_finyearamtgrants->fetch();
                            $total_amount = !is_null($row_finyearamtgrants["totalamt"]) ? $row_finyearamtgrants["totalamt"] : 0;
                            $total_amount = $total_amount > 0 ? round($total_amount / 1000000, 2) : 0;
                            array_push($amount_contributed, $total_amount);
                            $start_year++;
                        }
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
                $start_date = date("Y-m-d", strtotime($start_year . "-07-01"));
                $end_date = date("Y-m-d", strtotime($end_year . "-06-30"));
                $query_crfinyearalloc = $db->prepare("SELECT sum(b.budget) as totalamt FROM tbl_programs_based_budget b inner join tbl_programs g on g.progid=b.progid WHERE b.finyear=:finyear");
                $query_crfinyearalloc->execute(array(":finyear" => $start_year));
                $row_crfinyearalloc = $query_crfinyearalloc->fetch();
                $totalannualbudget =  !is_null($row_crfinyearalloc["totalamt"]) ? $row_crfinyearalloc["totalamt"] : 0;
                $totalannualbudget = $totalannualbudget > 0 ? round($totalannualbudget / 1000000, 2) : 0;

                $query_annualexpenditure = $db->prepare("SELECT sum(amount) AS totalexpend FROM tbl_payments_disbursed  WHERE date_paid >=:start_date AND date_paid <=:end_date");
                $query_annualexpenditure->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
                $row_annualexpenditure = $query_annualexpenditure->fetch();
                $totalannualexpenditure = !is_null($row_annualexpenditure["totalexpend"]) ? $row_annualexpenditure["totalexpend"] : 0;
                $totalannualexpenditure = $totalannualexpenditure > 0 ? round($totalannualexpenditure / 1000000, 2) : 0;

                array_push($totalannualbdg, (float)$totalannualbudget);
                array_push($totalannualexp, (float)$totalannualexpenditure);
                $start_year++;
            }
            return array("totalannualbdg" => $totalannualbdg, "totalannualexp" => $totalannualexp);
        }

        function financial_years(int $total_strategic_plan_years, int $strategic_plan_start_year)
        {
            $financial_years = array();
            $years = array();
            for ($i = 0; $i < $total_strategic_plan_years; $i++) {
                $year = $strategic_plan_start_year++;
                $financial_year = $year . "/" . ($year + 1);
                array_push($financial_years, $financial_year);
                array_push($years, $year);
            }
            return array("financial_years" => $financial_years, "years" => $years);
        }

        function widgets($stage_id)
        {
            global $db, $access_level;
            $query_rsprojects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.stage_id=:stage_id $access_level ");
            $query_rsprojects->execute(array(":stage_id" => $stage_id));
            $allprojects = $query_rsprojects->rowCount();
            return $allprojects;
        }

        function contractGuarantees()
        {
            global $db;
            $guarantees_expired = [];
            $guarantees_expiring = [];
            $guarantees_healthy = [];
            $guarantees_stmt = $db->prepare('SELECT * FROM tbl_contract_guarantees');
            $guarantees_stmt->execute();
            $guarantees_obj = $guarantees_stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($guarantees_obj as $key => $guarantee) {
                $start_date = $guarantee->start_date;
                $end_date = date('Y-m-d', strtotime("+$guarantee->duration days", strtotime($start_date)));
                $today = date('Y-m-d');
                $thirty_days = date('Y-m-d', strtotime("+30 days", strtotime($today)));
                // expired
                if ($today >= $end_date) {
                    array_push($guarantees_expired, $guarantee);
                }
                // expiring

                $date_differences = date_diff(date_create($end_date), date_create($thirty_days));
                $is_expiring = (int) $date_differences->format("%a");
                if ($is_expiring <= 30) {
                    array_push($guarantees_expiring, $guarantee);
                }
                // healthy
                $end_date = date('Y-m-d', strtotime("+$guarantee->duration days", strtotime($start_date)));
                $date_difference = date_diff(date_create($end_date), date_create($thirty_days));
                $is_healthy = (int) $date_difference->format("%a");
                if ($is_healthy >= 30 && $today <= $end_date) {
                    array_push($guarantees_healthy, $guarantee);
                }
            }

            return [
                'expired' => count($guarantees_expired),
                'expiring' => count($guarantees_expiring),
                'healthy' => count($guarantees_healthy)
            ];
        }


        $project_distribution_data = $tender_projects = $tender_cost = $budget_data = [];
        $allprojectsurl = $prjfyfrom = $prjfyto = $prjsc = $prjward = '';
        $financial_year_from = $financial_year_to = $level_one_id = $level_two_id = '';
        $budget_data =  project_cost_vs_department_distribution($access_level);
        $tender_cost = projects_cost_vs_tender_category($access_level);
        $tender_projects = projects_vs_tender_category($access_level);
        $risk_levels = get_risk_levels();
        $issue_status = get_issues_status();

        $financial_year_details = financial_years($total_strategic_plan_years, $strategic_plan_start_year);
        $strategic_plan_financial_years = json_encode($financial_year_details['financial_years']);
        $strategic_plan_years = json_encode($financial_year_details['years']);
        $funds_details = fund_sources();

        $current_financial_year_budget = current_financial_year($current_year);
        $last_financial_year_budget = previous_financial_year($last_year);


        $budget_expenditure = budget_vs_expenditure_per_year();
        $totalannualbdg = json_encode($budget_expenditure['totalannualbdg']);
        $totalannualexp = json_encode($budget_expenditure['totalannualexp']);

        $contract_guarantees = contractGuarantees();
        $contract_guarantees_expired = $contract_guarantees['expired'];
        $contract_guarantees_expiring = $contract_guarantees['expiring'];
        $contract_guarantees_healthy = $contract_guarantees['healthy'];
        $preinvestment_id = 0;
        $planned_id = 1;
        $ongoing_id = 2;
        $complete_id = 3;
?>
        <style>
            .highcharts-figure,
            .highcharts-data-table table {
                min-width: 310px;
                max-width: 800px;
                margin: 1em auto;
            }

            .highcharts-data-table table {
                font-family: Verdana, sans-serif;
                border-collapse: collapse;
                border: 1px solid #ebebeb;
                margin: 10px auto;
                text-align: center;
                width: 100%;
                max-width: 500px;
            }

            .highcharts-data-table caption {
                padding: 1em 0;
                font-size: 1.2em;
                color: #555;
            }

            .highcharts-data-table th {
                font-weight: 600;
                padding: 0.5em;
            }

            .highcharts-data-table td,
            .highcharts-data-table th,
            .highcharts-data-table caption {
                padding: 0.5em;
            }

            .highcharts-data-table thead tr,
            .highcharts-data-table tr:nth-child(even) {
                background: #f8f8f8;
            }

            .highcharts-data-table tr:hover {
                background: #f1f7ff;
            }
        </style>
        <!-- Morris Chart Css-->
        <link href="projtrac-dashboard/plugins/morrisjs/morris.css" rel="stylesheet" />
        <link rel="stylesheet" href="assets/custom css/dashboard.css">

        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?>
                        <?= $pageTitle ?>
                        <?php
                        // var_dump($issue_status);
                        ?>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group" style="float:right">
                            </div>
                        </div>
                    </h4>
                </div>
                <div class="row clearfix">
                    <div class="block-header">
                        <?= $results; ?>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="body">
                                <!-- ============================================================== -->
                                <!-- Start Page Content -->
                                <!-- ============================================================== -->
                                <div class="card" style="margin-bottom:10px">
                                    <div class="header">
                                        <h2>Projects Status</h2>
                                        <div class="row clearfix" style="margin-bottom:-20px">
                                            <form id="searchform" name="searchform" method="get" style="margin-top:10px" action="">
                                                <?= csrf_token_html(); ?>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <select name="level_one_id" id="level_one_id" onchange="get_level_two()" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px;" data-live-search="true">
                                                        <option value="">Select <?= $level1label ?></option>
                                                        <?= get_level_one($level_one_id) ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <select name="level_two_id" id="level_two_id" class="form-control show-tick " onchange="get_project_details()" style="border:#CCC thin solid; border-radius:5px;" data-live-search="true">
                                                        <option value="">Select <?= $level2label ?></option>
                                                    </select>
                                                </div>
                                            </form>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:-10px">
                                                <a href='view-dashboard-projects.php?stage=<?= base64_encode("projid54321{$preinvestment_id}") ?>' id="stage_one">
                                                    <div class="info-box bg-grey hover-expand-effect">
                                                        <div class="icon">
                                                            <i class="material-icons">hourglass_empty</i>
                                                        </div>
                                                        <div class="content">
                                                            <div class="text" style="font-size:13px;">Pre-Investment</div>
                                                            <div class="number count-to" data-from="0" data-to="<?php echo widgets(0); ?>" data-speed="1000" data-fresh-interval="20" id="pre_investment"><?php echo widgets(0); ?></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:-10px">
                                                <a href="view-dashboard-projects.php?stage=<?= base64_encode("projid54321{$planned_id}") ?>" id="stage_two">
                                                    <div class="info-box bg-brown hover-expand-effect">
                                                        <div class="icon">
                                                            <i class="material-icons">report_problem</i>
                                                        </div>
                                                        <div class="content">
                                                            <div class="text" style="font-size:16px;">Planned</div>
                                                            <div class="number count-to" data-from="0" data-to="<?php echo widgets(1); ?>" data-speed="1000" data-fresh-interval="20" id="planned"><?php echo widgets(1); ?></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:-10px">
                                                <a href='view-dashboard-projects.php?stage=<?= base64_encode("projid54321{$ongoing_id}") ?>' id="stage_three">
                                                    <div class="info-box bg-blue hover-expand-effect">
                                                        <div class="icon">
                                                            <i class="material-icons">timeline</i>
                                                        </div>
                                                        <div class="content">
                                                            <div class="text" style="font-size:16px;">On-Going</div>
                                                            <div class="number count-to" data-from="0" data-to="<?php echo widgets(2); ?>" data-speed="1000" data-fresh-interval="20" id="ongoing"><?php echo widgets(2); ?></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:-10px">
                                                <a href='view-dashboard-projects.php?stage=<?= base64_encode("projid54321{$complete_id}") ?>' id="stage_four">
                                                    <div class="info-box bg-light-green hover-expand-effect">
                                                        <div class="icon">
                                                            <i class="material-icons">verified_user</i>
                                                        </div>
                                                        <div class="content">
                                                            <div class="text" style="font-size:16px;">Completed</div>
                                                            <div class="number count-to" data-from="0" data-to="<?php echo widgets(3); ?>" data-speed="1000" data-fresh-interval="20" id="complete"><?php echo widgets(3); ?></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- #END# Widgets -->
                                </div>
                            </div>
                            <div class="row clearfix">
                                <!-- Pie Chart -->
                                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2 style="margin:5px">Risk Matrix</h2>
                                        </div>
                                        <div class="body">
                                            <figure class="highcharts-figure">
                                                <div id="container2"></div>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Bar Chart -->
                                <!-- Bar Chart -->
                                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2 style="margin:5px">Issues Status</h2>
                                        </div>
                                        <div class="body">
                                            <figure class="highcharts-figure">
                                                <div id="container"></div>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Bar Chart -->
                                <!-- Start of Fund Sources Per Year Column-->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2 style="margin:5px">Fund Sources (Ksh. in millions)</h2>
                                        </div>
                                        <div class="card-body" style="padding-top:0px">
                                            <div id="dynamicloaded" style="width:100%; height:400px;">
                                                <div id="fund_sources">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Fund Sources Per Year Column -->
                                <!-- Browser Usage -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2 style="margin:5px">Funds Absorption Rate Per Department</h2>
                                        </div>
                                        <div class="body">
                                            <div id="cost_vs_budget" class="dashboard-donut-chart" style="width:100%; height:400px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Browser Usage -->
                                <!-- column -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2 style="margin:5px">Budget Vs Expenditure Per Year</h2>
                                        </div>
                                        <div class="body" style="padding-top:10px; margin-left: 10px; margin-right: 10px;">
                                            <div id="budget_vs_expenditure" style="width:100%; height:400px;" align="center"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- column -->
                                <!-- Pie Chart -->
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2 style="margin:5px">Projects Per Tender Category</h2>
                                        </div>
                                        <div class="body">
                                            <div id="proj_no_tender_category" style="width:100%; height:400px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Bar Chart -->
                                <!-- Bar Chart -->
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2 style="margin:5px">Projects Cost Per Tender Category</h2>
                                        </div>
                                        <div class="body">
                                            <div id="proj_amt_tender_category" style="width: 100%; height: 400px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Bar Chart -->
                                <!-- Bar Chart -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2 style="margin:5px">Projects Contract Guarantees</h2>
                                        </div>
                                        <div class="body">
                                            <div id="proj_guarantees" style="width: 100%; height: 400px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Bar Chart -->
                            </div>

                            <!-- ============================================================== -->
                            <!-- End PAge Content -->
                            <!-- ============================================================== -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end body  -->

        <!-- Chart JS -->
        <script src="assets/plugins/echarts/echarts-all.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

        <!-- ChartJs -->
        <script src="projtrac-dashboard/plugins/chartjs/Chart.bundle.js"></script>

        <!-- Sparkline Chart Plugin Js -->
        <script src="projtrac-dashboard/plugins/jquery-sparkline/jquery.sparkline.js"></script>
        <script src="https://www.gstatic.com/charts/loader.js"></script>

        <!-- Morris Plugin Js -->
        <script src="projtrac-dashboard/plugins/raphael/raphael.min.js"></script>

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.highcharts.com/modules/heatmap.js"></script>

<?php

    } else {
        $results =  restriction();
        echo $results;
    }
    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="./assets/js/dashboard/index.js"></script>
<script>
    let financial_years = '<?= $strategic_plan_financial_years ?>';
    financial_years = JSON.parse(financial_years);
    let fyears = '<?= $strategic_plan_years ?>';
    fyears = JSON.parse(fyears);

    let funds_details = '<?= $funds_details ?>';
    funds_details = JSON.parse(funds_details);

    let totalannualexp = '<?= $totalannualexp ?>';
    totalannualexp = JSON.parse(totalannualexp);
    let totalannualbdg = '<?= $totalannualbdg ?>';
    totalannualbdg = JSON.parse(totalannualbdg);
    let budget_data = <?= $budget_data ?>;
    let issue_status = <?= $issue_status ?>;
    let tender_projects = <?= $tender_projects ?>;
    let tender_cost = <?= $tender_cost ?>;

    let contract_guarantees_expired = Number(<?= $contract_guarantees_expired ?>);
    let contract_guarantees_expiring = Number(<?= $contract_guarantees_expiring ?>);
    let contract_guarantees_healthy = Number(<?= $contract_guarantees_healthy ?>);
</script>