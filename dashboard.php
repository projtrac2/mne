<?php
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
    $pageTitle = "General Dashboard";
    try {
        function projfy()
        {
            global $db;
            $projfy = $db->prepare("SELECT * FROM tbl_fiscal_year");
            $projfy->execute();
            while ($row = $projfy->fetch()) {
                echo '<option value="' . $row['id'] . '">' . $row['year'] . '</option>';
            }
        }

        function departments()
        {
            global $db;
            $departments = [];
            $query_rsDepartments = $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 ORDER BY sector ASC");
            $query_rsDepartments->execute();
            $row_rsDepartments = $query_rsDepartments->fetch();
            $totalRows_rsDepartments = $query_rsDepartments->rowCount();

            if ($totalRows_rsDepartments > 0) {
                do {
                    $departments[] = $row_rsDepartments['sector'];
                } while ($row_rsDepartments = $query_rsDepartments->fetch());
            }
            return json_encode($departments);
        }

        //get subcounty
        $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
        $query_rsComm->execute();
        $row_rsComm = $query_rsComm->fetch();
        $totalRows_rsComm = $query_rsComm->rowCount();

        $status_array = array(
            'all' => array('stage' => '', "status" => ''),
            'complete' => array('stage' => '', "status" => 5),
            'in-progress' => array('stage' => '= 10', "status" => 4),
            'pending' => array('stage' => '> 6', "status" => '0'),
            'behind-schedule' => array('stage' => '=10', "status" => 11),
            'approved' => array('stage' => '< 7', "status" => '0'),
            'on-hold' => array('stage' => '= 10', "status" => 6),
            'cancelled' => array('stage' => '= 10', "status" => 2),
        );

        function widgets_filter($from = null, $to = null, $level1 = null, $level2 = null, $level3 = null, $prjstatus)
        {
            $widget_array = '';
            if ($from != null) {
                if ($to != null) {
                    if ($level1 != null) {
                        if ($level2 != null) {
                            if ($level3 != null) {
                                // select for only from, to, level 1, 2 and 3
                                $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                                $widget_array = widgets($sql, $level1, $level2, $level3, $prjstatus);
                                $department_projects = project_vs_department_distribution($sql, $level1, $level2, $level3, $prjstatus);
                                $budget_data = project_cost_vs_department_distribution($sql, $level1, $level2, $level3, $prjstatus);
                                $tender_projects = projects_vs_tender_category($sql, $level1, $level2, $level3, $prjstatus);
                                $tender_cost = projects_cost_vs_tender_category($sql, $level1, $level2, $level3, $prjstatus);
                            } else {
                                // select for only from, to, level 1 and 2
                                $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                                $widget_array = widgets($sql, $level1, $level2, $level3 = null, $prjstatus);
                                $department_projects = project_vs_department_distribution($sql, $level1, $level2, $level3 = null, $prjstatus);
                                $budget_data = project_cost_vs_department_distribution($sql, $level1, $level2, $level3 = null, $prjstatus);
                                $tender_projects = projects_vs_tender_category($sql, $level1, $level2, $level3 = null, $prjstatus);
                                $tender_cost = projects_cost_vs_tender_category($sql, $level1, $level2, $level3 = null, $prjstatus);
                            }
                        } else {
                            // select for only from, to and level 1
                            $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                            $widget_array = widgets($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                            $department_projects = project_vs_department_distribution($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                            $budget_data = project_cost_vs_department_distribution($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                            $tender_projects = projects_vs_tender_category($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                            $tender_cost = projects_cost_vs_tender_category($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                        }
                    } else {
                        // select for only from, to and to
                        $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                        $widget_array = widgets($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                        $department_projects = project_vs_department_distribution($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                        $budget_data = project_cost_vs_department_distribution($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                        $tender_projects = projects_vs_tender_category($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                        $tender_cost = projects_cost_vs_tender_category($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                    }
                } else {
                    if ($level1 != null) {
                        if ($level2 != null) {
                            if ($level3 != null) {
                                // select for only from, level 1, 2 and 3
                                $sql = "p.projfscyear >=" . $from;
                                $widget_array = widgets($sql, $level1, $level2, $level3, $prjstatus);
                                $department_projects = project_vs_department_distribution($sql, $level1, $level2, $level3, $prjstatus);
                                $budget_data = project_cost_vs_department_distribution($sql, $level1, $level2, $level3, $prjstatus);
                                $tender_projects = projects_vs_tender_category($sql, $level1, $level2, $level3, $prjstatus);
                                $tender_cost = projects_cost_vs_tender_category($sql, $level1, $level2, $level3, $prjstatus);
                            } else {
                                // select for only from, level 1 and 2
                                $sql = "p.projfscyear >=" . $from;
                                $widget_array = widgets($sql, $level1, $level2, $level3 = null, $prjstatus);
                                $department_projects = project_vs_department_distribution($sql, $level1, $level2, $level3 = null, $prjstatus);
                                $budget_data = project_cost_vs_department_distribution($sql, $level1, $level2, $level3 = null, $prjstatus);
                                $tender_projects = projects_vs_tender_category($sql, $level1, $level2, $level3 = null, $prjstatus);
                                $tender_cost = projects_cost_vs_tender_category($sql, $level1, $level2, $level3 = null, $prjstatus);
                            }
                        } else {
                            // select for only from and level 1
                            $sql = "p.projfscyear >=" . $from;
                            $widget_array = widgets($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                            $department_projects = project_vs_department_distribution($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                            $budget_data = project_cost_vs_department_distribution($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                            $tender_projects = projects_vs_tender_category($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                            $tender_cost = projects_cost_vs_tender_category($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                        }
                    } else {
                        // select for only from
                        $sql = "p.projfscyear >=" . $from;
                        $widget_array = widgets($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                        $department_projects = project_vs_department_distribution($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                        $budget_data = project_cost_vs_department_distribution($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                        $tender_projects = projects_vs_tender_category($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                        $tender_cost = projects_cost_vs_tender_category($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                    }
                }
            } else {
                if ($level1 != null) {
                    if ($level2 != null) {
                        if ($level3 != null) {
                            // select for only level 1, 2 and 3
                            $widget_array = widgets($sql = null, $level1, $level2, $level3, $prjstatus);
                            $department_projects = project_vs_department_distribution($sql = null, $level1, $level2, $level3, $prjstatus);
                            $budget_data = project_cost_vs_department_distribution($sql = null, $level1, $level2, $level3, $prjstatus);
                            $tender_projects = projects_vs_tender_category($sql = null, $level1, $level2, $level3, $prjstatus);
                            $tender_cost = projects_cost_vs_tender_category($sql = null, $level1, $level2, $level3, $prjstatus);
                        } else {
                            // select for only level 1 and 2
                            $widget_array = widgets($sql = null, $level1, $level2, $level3 = null, $prjstatus);
                            $department_projects = project_vs_department_distribution($sql = null, $level1, $level2, $level3 = null, $prjstatus);
                            $budget_data = project_cost_vs_department_distribution($sql = null, $level1, $level2, $level3 = null, $prjstatus);
                            $tender_projects = projects_vs_tender_category($sql = null, $level1, $level2, $level3 = null, $prjstatus);
                            $tender_cost = projects_cost_vs_tender_category($sql = null, $level1, $level2, $level3 = null, $prjstatus);
                        }
                    } else {
                        // select for only level 1
                        $widget_array = widgets($sql = null, $level1, $level2 = null, $level3 = null, $prjstatus);
                        $department_projects = project_vs_department_distribution($sql = null, $level1, $level2 = null, $level3 = null, $prjstatus);
                        $budget_data = project_cost_vs_department_distribution($sql = null, $level1, $level2 = null, $level3 = null, $prjstatus);
                        $tender_projects = projects_vs_tender_category($sql = null, $level1, $level2 = null, $level3 = null, $prjstatus);
                        $tender_cost = projects_cost_vs_tender_category($sql = null, $level1, $level2 = null, $level3 = null, $prjstatus);
                    }
                }
            }


            return array(
                "widget_data" => $widget_array,
                "department_projects" => $department_projects,
                "budget_data" => $budget_data,
                "tender_projects" => $tender_projects,
                "tender_cost" => $tender_cost,
            );
        }

        function widgets($query = null, $level1 = null, $level2 = null, $level3 = null)
        {
            global $status_array, $db;
            $widgets = array();
            foreach ($status_array as $key => $project_status) {
                $stage = $project_status['stage'];
                $status = $project_status['status'];
                $stmt = '';
                if ($status != '' && $stage != '') {
                    $stmt = "projstatus =" . $status . ' AND projstage ' . $stage;
                } else if ($status != '' && $stage == '') {
                    $stmt = "projstatus =" . $status;
                } else if ($status == '' && $stage != '') {
                    $stmt = 'projstage ' . $stage;
                }

                $where = $stmt != "" ? "AND " . $stmt : "";
                $where = $query != null ? $where . " AND " . $query : $where;

                $sql = "SELECT * FROM tbl_projects p INNER JOIN tbl_annual_dev_plan d ON p.projid= d.projid  WHERE d.status = 1 and deleted = '0' " . $where;
                // echo $sql . "<br/>";
                $query_rsprojects = $db->prepare($sql);
                $query_rsprojects->execute();
                $row_projects = $query_rsprojects->fetch();
                $allprojects = $query_rsprojects->rowCount();
                // echo $sql . "  Status = " . $key  . "  projects count  " . $allprojects . "<br/> ";

                if ($allprojects > 0) {
                    if ($level1 != null) {
                        $count_projects = 0;
                        do {
                            $projcommunity = explode(",", $row_projects['projcommunity']);
                            $projlga = explode(",", $row_projects['projlga']);
                            $projstate = explode(",", $row_projects['projstate']);

                            if ($level2 != null) {
                                if ($level3 != null) {
                                    if (in_array($level3, $projstate)) {
                                        $count_projects += 1;
                                    }
                                } else {
                                    if (in_array($level2, $projlga)) {
                                        $count_projects += 1;
                                    }
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

        function project_cost_vs_department_distribution($query = null, $level1 = null, $level2 = null, $level3 = null, $projstatus = null)
        {
            global $db;
            $query_rsDepartments = $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 ORDER BY sector ASC");
            $query_rsDepartments->execute();
            $row_rsDepartments = $query_rsDepartments->fetch();
            $totalRows_rsDepartments = $query_rsDepartments->rowCount();
            $rate = [];
            if ($totalRows_rsDepartments > 0) {
                $where = "";
                $where  = $query != null ? $where . " AND " . $query : $where;
                $budget_data = "";
                do {
                    $stid = $row_rsDepartments['stid'];
                    $sector = $row_rsDepartments['sector'];
                    $sql = "SELECT  * FROM tbl_projects p
                    INNER JOIN tbl_annual_dev_plan d ON p.projid= d.projid 
                    INNER JOIN tbl_programs g ON p.progid= g.progid  
                    WHERE d.status = 1  AND g.projsector='$stid'  $where";
                    $query_rsprojects = $db->prepare($sql);
                    $query_rsprojects->execute();
                    $row_projects = $query_rsprojects->fetch();
                    $allprojects = $query_rsprojects->rowCount();
                    $count_projects = 0;
                    $k = $p = 0;

                    if ($allprojects > 0) {
                        do {
                            $projcommunity = explode(",", $row_projects['projcommunity']);
                            $projlga = explode(",", $row_projects['projlga']);
                            $projstate = explode(",", $row_projects['projstate']);
                            $projid = $row_projects['projid'];

                            $query_rsApprovedCost = $db->prepare("SELECT  sum(amount) as amount FROM tbl_project_approved_yearly_budget WHERE projid=$projid ");
                            $query_rsApprovedCost->execute();
                            $row_rsApprovedCost = $query_rsApprovedCost->fetch();
                            $totalRows_rsApprovedCost = $query_rsApprovedCost->rowCount();

                            $query_rsPayouts = $db->prepare("SELECT sum(amountpaid) as amount FROM tbl_payments_disbursed WHERE projid=$projid");
                            $query_rsPayouts->execute();
                            $row_rsPayouts = $query_rsPayouts->fetch();
                            $totalRows_rsPayouts = $query_rsPayouts->rowCount();
                            $amount_paid = $row_rsPayouts['amount'];
                            $amount_approved = $row_rsApprovedCost['amount'];
                            if ($amount_approved > 0 && $amount_paid > 0) {
                                if ($level1 != null) {
                                    if ($level2 != null) {
                                        if ($level3 != null) {
                                            if (in_array($level3, $projstate)) {
                                                $k += $amount_paid;
                                                $p += $amount_approved;
                                            }
                                        } else {
                                            if (in_array($level2, $projlga)) {
                                                $k += $amount_paid;
                                                $p += $amount_approved;
                                            }
                                        }
                                    } else {
                                        if (in_array($level1, $projcommunity)) {
                                            $k += $amount_paid;
                                            $p += $amount_approved;
                                        }
                                    }
                                } else {
                                    $k += $amount_paid;
                                    $p += $amount_approved;
                                }
                            }
                        } while ($row_projects = $query_rsprojects->fetch());
                    }
                    $rate = ($k != 0 && $p != 0) ? ($k / $p * 100) : 0;
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
                } while ($row_rsDepartments = $query_rsDepartments->fetch());
            }
            return $budget_data;
        }

        function project_vs_department_distribution($query = null, $level1 = null, $level2 = null, $level3 = null, $projstatus = null)
        {
            global $db;
            $query_rsDepartments = $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 ORDER BY sector ASC");
            $query_rsDepartments->execute();
            $row_rsDepartments = $query_rsDepartments->fetch();
            $totalRows_rsDepartments = $query_rsDepartments->rowCount();
            $department_arr = [];
            if ($totalRows_rsDepartments > 0) {
                $where = "";
                $where  = $query != null ? $where . " AND " . $query : $where;
                do {
                    $stid = $row_rsDepartments['stid'];
                    $sql = "SELECT * FROM tbl_projects p
                    INNER JOIN tbl_annual_dev_plan d ON p.projid= d.projid
                    INNER JOIN tbl_programs g ON p.progid= g.progid
                    WHERE d.status = 1  AND g.projsector='$stid' $where";
                    $query_rsprojects = $db->prepare($sql);
                    $query_rsprojects->execute();
                    $row_projects = $query_rsprojects->fetch();
                    $allprojects = $query_rsprojects->rowCount();
                    $count_projects = 0;
                    if ($allprojects > 0) {
                        do {
                            $projcommunity = explode(",", $row_projects['projcommunity']);
                            $projlga = explode(",", $row_projects['projlga']);
                            $projstate = explode(",", $row_projects['projstate']);
                            if ($level1 != null) {
                                if ($level2 != null) {
                                    if ($level3 != null) {
                                        if (in_array($level3, $projstate)) {
                                            $count_projects += 1;
                                        }
                                    } else {
                                        if (in_array($level2, $projlga)) {
                                            $count_projects += 1;
                                        }
                                    }
                                } else {
                                    if (in_array($level1, $projcommunity)) {
                                        $count_projects += 1;
                                    }
                                }
                            } else {
                                $count_projects += 1;
                            }
                        } while ($row_projects = $query_rsprojects->fetch());
                    }
                    $department_arr[] = $count_projects;
                } while ($row_rsDepartments = $query_rsDepartments->fetch());
            }
            return json_encode($department_arr);
        }

        function projects_cost_vs_tender_category($query = null, $level1 = null, $level2 = null, $level3 = null)
        {
            global $db;
            $data_no_arr = "[['Tenders', 'Project Amount'],";
            $query_tender_category = $db->prepare("SELECT * FROM tbl_tender_category WHERE status = 1");
            $query_tender_category->execute();
            $count_tender_category = $query_tender_category->rowCount();
            $rows_tender_category = $query_tender_category->fetch();

            if ($count_tender_category > 0) {
                $where = "";
                $where  = $query != null ? $where . " AND " . $query : $where;
                do {
                    $tender_catid = $rows_tender_category['id'];
                    $t = $rows_tender_category['category'];
                    $sql = "SELECT * FROM tbl_projects p INNER JOIN tbl_annual_dev_plan d ON p.projid= d.projid  WHERE d.status = 1 and deleted = '0' $where ";
                    $query_rsprojects = $db->prepare($sql);
                    $query_rsprojects->execute();
                    $row_projects = $query_rsprojects->fetch();
                    $allprojects = $query_rsprojects->rowCount();

                    if ($allprojects > 0) {
                        $n = 0;
                        do {
                            $projcommunity = explode(",", $row_projects['projcommunity']);
                            $projlga = explode(",", $row_projects['projlga']);
                            $projstate = explode(",", $row_projects['projstate']);
                            $projid = $row_projects['projid'];
                            $query_tender_projects = $db->prepare("SELECT SUM((unit_cost * units_no)) as totalamt FROM tbl_projects p
                                INNER JOIN tbl_tenderdetails t  ON p.projtender=t.td_id
                                INNER JOIN tbl_project_tender_details d  ON p.projid=d.projid
                                INNER JOIN tbl_tender_category tt ON t.tendercat=tt.id
                                INNER JOIN tbl_tender_type tc ON t.tendertype=tc.id
                                WHERE  tt.id='$tender_catid' AND p.projid='$projid'");
                            $query_tender_projects->execute();
                            $count_tender_projects = $query_tender_projects->rowCount();
                            $rows_tender_projects = $query_tender_projects->fetch();
                            $amt = $rows_tender_projects['totalamt'];
                            $amts = 0;
                            if ($amt != null) {
                                $amts = $amt;
                            }
                            if ($count_tender_projects > 0) {
                                if ($level1 != null) {
                                    if ($level2 != null) {
                                        if ($level3 != null) {
                                            if (in_array($level3, $projstate)) {
                                                $n += $amts;
                                            }
                                        } else {
                                            if (in_array($level2, $projlga)) {
                                                $n += $amts;
                                            }
                                        }
                                    } else {
                                        if (in_array($level1, $projcommunity)) {
                                            $n += $amts;
                                        }
                                    }
                                } else {
                                    $n += $amts;
                                }
                            }
                        } while ($row_projects = $query_rsprojects->fetch());
                        $data_no_arr .= "['" . $t . "', " . $n . "],";
                    } else {
                        $n = 0;
                        $data_no_arr .= "['" . $t . "', " . $n . "],";
                    }
                } while ($rows_tender_category = $query_tender_category->fetch());
            }
            $data_no_arr .= ']';
            return $data_no_arr;
        }

        function projects_vs_tender_category($query = null, $level1 = null, $level2 = null, $level3 = null)
        {
            global $db;
            $data_no_arr = "[['Tenders', 'Project Number'],";
            $query_tender_category = $db->prepare("SELECT * FROM tbl_tender_category WHERE status = 1 ");
            $query_tender_category->execute();
            $count_tender_category = $query_tender_category->rowCount();
            $rows_tender_category = $query_tender_category->fetch();

            if ($count_tender_category > 0) {
                $where = "";
                $where  = $query != null ? $where . " AND " . $query : $where;
                do {
                    $tender_catid = $rows_tender_category['id'];
                    $t = $rows_tender_category['category'];
                    $sql = "SELECT * FROM tbl_projects p INNER JOIN tbl_annual_dev_plan d ON p.projid= d.projid  WHERE d.status = 1 and deleted = '0' $where ";
                    $query_rsprojects = $db->prepare($sql);
                    $query_rsprojects->execute();
                    $row_projects = $query_rsprojects->fetch();
                    $allprojects = $query_rsprojects->rowCount();


                    if ($allprojects > 0) {
                        $n = 0;
                        do {
                            $projcommunity = explode(",", $row_projects['projcommunity']);
                            $projlga = explode(",", $row_projects['projlga']);
                            $projstate = explode(",", $row_projects['projstate']);
                            $projid = $row_projects['projid'];
                            $query_tender_projects = $db->prepare("SELECT projid, tt.id FROM tbl_tenderdetails t INNER JOIN tbl_tender_category tt ON t.tendercat=tt.id  INNER JOIN tbl_tender_type tc ON t.tendertype=tc.id AND tt.id='$tender_catid' AND projid='$projid'");
                            $query_tender_projects->execute();
                            $count_tender_projects = $query_tender_projects->rowCount();
                            $rows_tender_projects = $query_tender_projects->fetch();
                            if ($count_tender_projects > 0) {
                                if ($level1 != null) {
                                    if ($level2 != null) {
                                        if ($level3 != null) {
                                            if (in_array($level3, $projstate)) {
                                                $n += 1;
                                            }
                                        } else {
                                            if (in_array($level2, $projlga)) {
                                                $n += 1;
                                            }
                                        }
                                    } else {
                                        if (in_array($level1, $projcommunity)) {
                                            $n += 1;
                                        }
                                    }
                                } else {
                                    $n += 1;
                                }
                            }
                        } while ($row_projects = $query_rsprojects->fetch());
                        $data_no_arr .= "['" . $t . "', " . $n . "],";
                    } else {
                        $n = 0;
                        $data_no_arr .= "['" . $t . "', " . $n . "],";
                    }
                } while ($rows_tender_category = $query_tender_category->fetch());
            }
            $data_no_arr .= ']';
            return $data_no_arr;
        }

        $project_distribution_data = $tender_projects = $tender_cost = $budget_data = [];
        $allprojectsurl = '';
        if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
            // $prjstatus = $_GET['prjstatus'];
            $prjstatus = '';
            $prjfyfrom = $_GET['projfyfrom'];
            $prjfyto = $_GET['projfyto'];
            $prjsc = $_GET['projscounty'];
            $prjward = $_GET['projward'];
            $prjloc = $_GET['projlocation'];
            $allprojectsurl = "&projfyfrom=$prjfyfrom&projfyto=$prjfyto&projscounty=$prjsc&projward=$prjward&projlocation=$prjloc&btn_search=FILTER";
            if (empty($prjfyfrom) && empty($prjsc)) {
                $widget_data = widgets($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
                $project_distribution_data = project_vs_department_distribution($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
                $budget_data = project_cost_vs_department_distribution($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
                $tender_projects = projects_vs_tender_category($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
                $tender_cost = projects_cost_vs_tender_category($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
            } else {
                $widget_data1 = widgets_filter($prjfyfrom, $prjfyto, $prjsc, $prjward, $prjloc, $prjstatus);
                $widget_data = $widget_data1['widget_data'];
                $project_distribution_data = $widget_data1['department_projects'];
                $budget_data = $widget_data1['budget_data'];
                $tender_projects = $widget_data1['tender_projects'];
                $tender_cost = $widget_data1['tender_cost'];
            }
        } else {
            $prjstatus = 'all';
            if (isset($_GET['prjstatus'])) {
                $prjstatus = $_GET['prjstatus'];
            }

            $widget_data = widgets($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
            $project_distribution_data = project_vs_department_distribution($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
            $budget_data = project_cost_vs_department_distribution($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
            $tender_projects = projects_vs_tender_category($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
            $tender_cost = projects_cost_vs_tender_category($query = null, $prjsc = null, $prjward = null, $prjloc = null, $prjstatus);
        }

        include_once 'system-labels.php';
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>

    <!-- Morris Chart Css-->
    <link href="projtrac-dashboard/plugins/morrisjs/morris.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/custom css/dashboard.css">

    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?>
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

                            <div class="header">
                                <h2>
                                    USE BELOW SELECTION TO FILTER THE DASHBOARD
                                </h2>
                                <div class="row clearfix">
                                    <form id="searchform" name="searchform" method="get" style="margin-top:10px" action="">
                                        <div class="col-md-4">
                                            <select name="projfyfrom" id="fyfrom" onchange="finyearfrom()" class="form-control show-tick " data-live-search="true" style="border:#CCC thin solid; border-radius:5px;" data-live-search-style="startsWith">
                                                <option value="" selected="selected">Select Financial Year From</option>
                                                <?php
                                                projfy();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="projfyto" id="fyto" class="form-control show-tick" data-live-search="false" style="border:#CCC thin solid; border-radius:5px;" data-live-search-style="startsWith">
                                                <option value="" selected="selected">Select To Financial Year</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="projscounty" id="projcommunity" onchange="conservancy()" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px;" data-live-search="false">
                                                <option value="">Select <?= $level1label ?></option>
                                                <?php
                                                $data = '';
                                                $id = [];
                                                do {
                                                    $comm = $row_rsComm['id'];
                                                    $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm ");
                                                    $query_ward->execute(array(":comm" => $comm));
                                                    while ($row = $query_ward->fetch()) {
                                                        $projlga = $row['id'];
                                                        $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                                                        $query_rsLocations->execute(array(":id" => $projlga));
                                                        $row_rsLocations = $query_rsLocations->fetch();
                                                        $total_locations = $query_rsLocations->rowCount();
                                                        if ($total_locations > 0) {
                                                            if (!in_array($comm, $id)) {
                                                                $data .= '<option value="' . $row_rsComm['id'] . '">' . $row_rsComm['state'] . '</option>';
                                                            }
                                                            $id[] = $row_rsComm['id'];
                                                        }
                                                    }
                                                } while ($row_rsComm = $query_rsComm->fetch());
                                                echo $data;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="projward" id="projlga" onchange="ecosystem()" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px;" data-live-search="false">
                                                <option value="">Select <?= $level2label ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="projlocation" class="form-control show-tick" data-live-search="false" id="projloc" style="border:#CCC thin solid; border-radius:5px;">
                                                <option value="" selected="selected">Select <?= $level3label ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="FILTER" />
                                            <input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='dashboard.php'" id="btnback">
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Widgets -->
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href="view-dashboard-projects.php?prjstatus=all&<?php echo $allprojectsurl; ?>">
                                        <div class="info-box bg-deep-purple hover-expand-effect">
                                            <div class="icon">
                                                <i class="material-icons">playlist_add_check</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">ALL PROJECTS </div>
                                                <div class="number count-to" data-from="0" data-to="<?php echo $widget_data['all']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $widget_data['all']; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href='view-dashboard-projects.php?prjstatus=complete&<?php echo $allprojectsurl; ?>'>
                                        <div class="info-box bg-light-green hover-expand-effect">
                                            <div class="icon">
                                                <i class="material-icons">verified_user</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">COMPLETED PROJECTS</div>
                                                <div class="number count-to" data-from="0" data-to="<?php echo $widget_data['complete']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $widget_data['complete']; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href='view-dashboard-projects.php?prjstatus=in-progress&<?php echo $allprojectsurl; ?>'>
                                        <div class="info-box bg-blue hover-expand-effect">
                                            <div class="icon">
                                                <i class="material-icons">timeline</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">ON TRACK PROJECTS</div>
                                                <div class="number count-to" data-from="0" data-to="<?php echo $widget_data['in-progress']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $widget_data['in-progress']; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href='view-dashboard-projects.php?prjstatus=pending&<?php echo $allprojectsurl; ?>'>
                                        <div class="info-box bg-yellow hover-expand-effect">
                                            <div class="icon">
                                                <i class="material-icons">schedule</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">PENDING PROJECTS</div>
                                                <div class="number count-to" data-from="0" data-to="<?php echo $widget_data['pending']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $widget_data['pending']; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href='view-dashboard-projects.php?prjstatus=behind-schedule&<?php echo $allprojectsurl; ?>'>
                                        <div class="info-box bg-red hover-expand-effect">
                                            <div class="icon">
                                                <i class="material-icons">help</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">PROJECTS BEHIND SCHEDULE</div>
                                                <div class="number count-to" data-from="0" data-to="<?php echo $widget_data['behind-schedule']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $widget_data['behind-schedule']; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href='view-dashboard-projects.php?prjstatus=approved&<?php echo $allprojectsurl; ?>'>
                                        <div class="info-box bg-grey hover-expand-effect">
                                            <div class="icon">
                                                <i class="material-icons">hourglass_empty</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">AWAITING PROCUREMENT</div>
                                                <div class="number count-to" data-from="0" data-to="<?php echo $widget_data['approved']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $widget_data['approved']; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href="view-dashboard-projects.php?prjstatus=on-hold&<?php echo $allprojectsurl; ?>">
                                        <div class="info-box bg-pink hover-expand-effect">
                                            <div class="icon">
                                                <i class="material-icons">pan_tool</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">PROJECTS ON HOLD</div>
                                                <div class="number count-to" data-from="0" data-to="<?php echo $widget_data['on-hold']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $widget_data['on-hold']; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href="view-dashboard-projects.php?prjstatus=cancelled&<?php echo $allprojectsurl; ?>">
                                        <div class="info-box bg-brown hover-expand-effect">
                                            <div class="icon">
                                                <i class="material-icons">report_problem</i>
                                            </div>
                                            <div class="content">
                                                <div class="text">CANCELLED PROJECTS</div>
                                                <div class="number count-to" data-from="0" data-to="<?php echo $widget_data['cancelled']; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $widget_data['cancelled']; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- #END# Widgets -->

                            <div class="row clearfix">
                                <!-- Browser Usage -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h5 class="card-title" style="margin:5px"><strong>Funds Absorption Rate Per Department</strong></h5>
                                        </div>
                                        <div class="body">
                                            <div id="cost_vs_budget" class="dashboard-donut-chart" style="width:100%; height:400px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Browser Usage -->
                                <!-- Radar Chart -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h5 class="card-title" style="margin:5px"><strong>Distribution of Projects by <?= $ministrylabelplural ?> (Numbers)</strong></h5>
                                        </div>
                                        <div class="body">
                                            <canvas id="radar_chart" style="width:100%; height:400px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Radar Chart -->

                                <!-- Pie Chart -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="card">
                                        <div class="header">
                                            <h5 class="card-title" style="margin:5px"><strong>Projects Per Tender Category</strong></h5>
                                        </div>
                                        <div class="body">
                                            <div id="proj_no_tender_category" style="width:100%; height:400px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Bar Chart -->
                                <!-- Bar Chart -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="card">
                                        <div class="header">
                                            <h5 class="card-title" style="margin:5px"><strong>Projects' Cost Per Tender Category</strong></h5>
                                        </div>
                                        <div class="body">
                                            <div id="proj_amt_tender_category" style="width: 100%; height: 400px;"></div>
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
<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>

<!-- Chart JS -->
<script src="assets/plugins/echarts/echarts-all.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

<!-- ChartJs -->
<script src="projtrac-dashboard/plugins/chartjs/Chart.bundle.js"></script>

<!-- Sparkline Chart Plugin Js -->
<script src="projtrac-dashboard/plugins/jquery-sparkline/jquery.sparkline.js"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>


<script src="assets/js/dashboard/dashboard.js"></script>

<!-- Morris Plugin Js -->
<script src="projtrac-dashboard/plugins/raphael/raphael.min.js"></script>

<script>
    $(document).ready(function() {
        projects_per_department();
        budget();
    });

    function budget() {
        var options = {
            series: [{
                name: 'Actual',
                data: [<?= $budget_data ?>]
            }],
            chart: {
                height: 500,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%'
                }
            },
            yaxis: {
                forceNiceScale: false,
                max: 100,
                labels: {
                    formatter: (value) => value.toFixed(0) + '%',
                },
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true,
                showForSingleSeries: true,
                customLegendItems: ['Actual', 'Expected'],
                markers: {
                    fillColors: ['#00E396', '#775DD0']
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#cost_vs_budget"), options);
        chart.render();
    }

    function projects_per_department() {
        var config = {
            type: 'radar',
            data: {
                labels: <?php echo departments(); ?>,
                datasets: [{
                    label: " Project",
                    data: <?php echo $project_distribution_data; ?>,
                    borderColor: 'rgba(0, 188, 212, 0.8)',
                    backgroundColor: 'rgba(0, 188, 212, 0.5)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.8)',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
        new Chart(document.getElementById("radar_chart").getContext("2d"), config);
    }

    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);


    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?= $tender_projects ?>);
        var options = {
            title: 'Distribution of projects per tender category (Number & Percentage)',
            is3D: false,
            sliceVisibilityThreshold: 0
        };

        var chart = new google.visualization.PieChart(document.getElementById('proj_no_tender_category'));
        chart.draw(data, options);
    }

    google.charts.load("current", {
        packages: ["corechart"]
    });

    google.charts.setOnLoadCallback(chartdraw);

    function chartdraw() {
        var data = google.visualization.arrayToDataTable(<?= $tender_cost ?>);
        var options = {
            title: 'Distribution Projects cost per tender category (Ksh & Percentage)',
            is3D: false,
            sliceVisibilityThreshold: 0
        };
        var chart = new google.visualization.PieChart(document.getElementById('proj_amt_tender_category'));
        chart.draw(data, options);
    }
</script>