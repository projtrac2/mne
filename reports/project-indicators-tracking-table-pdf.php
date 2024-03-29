<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//include_once 'projtrac-dashboard/resource/session.php';
session_start();
$user_name = $_SESSION['MM_Username'];

date_default_timezone_set("Africa/Nairobi");
include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php';

$stylesheet = file_get_contents('bootstrap.css'); // external css

try {
    function get_department($stid = null)
    {
        global $db;
        if ($stid) {
            $query_rsSectror =  $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = :stid ");
            $query_rsSectror->execute(array(":stid" => $stid));
            $row_rsSector = $query_rsSectror->fetch();
            $count_rsSector = $query_rsSectror->rowCount();
            if ($count_rsSector > 0) {
                return $row_rsSector['sector'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_financial_year($fyid = null)
    {
        global $db;
        if ($fyid) {
            $query_rsFscYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year where id =:year ");
            $query_rsFscYear->execute(array(":year" => $fyid));
            $row_rsFscYear = $query_rsFscYear->fetch();
            $count_rsFscYear = $query_rsFscYear->rowCount();
            if ($count_rsFscYear > 0) {
                return $row_rsFscYear['year'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function message($get = null, $sector = null, $dept = null, $year = null, $projfyto = null)
    {
        $filter = '<h4 style="color:green" align="left">Filters </h4>';
        if ($get) {
            if ($year) {
                $fsc_year = get_financial_year($year);
                $filter .= '<h4>From Financial Year:<small>' . $fsc_year . '</small></h4>';
                if ($projfyto) {
                    $fsc_year = get_financial_year($projfyto);
                    $filter .= '<h4>To Financial Year:<small>' . $fsc_year . '</small></h4>';
                }
            }

            if ($sector != null) {
                $sect = get_department($sector);
                $filter .= '<h4>Sector:<small>' . $sect . '</small></h4>';
                if ($dept) {
                    $department = get_department($dept);
                    $filter .= '<h4>Department:<small>' . $department . '</small> </h4>';
                }
            }
            return $filter;
        } else {
            return false;
        }
    }

    function get_status($status_id)
    {
        global $db;
        $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :status_id");
        $query_Projstatus->execute(array(":status_id" => $status_id));
        $row_Projstatus = $query_Projstatus->fetch();
        $total_Projstatus = $query_Projstatus->rowCount();
        $status = "";
        if ($total_Projstatus > 0) {
            $status_name = $row_Projstatus['statusname'];
            $status_class = $row_Projstatus['class_name'];
            $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
        }
        return $status;
    }



    function get_subtask_status($progress, $start_date, $end_date, $status, $project_status)
    {
        $today = date('Y-m-d');
        if ($project_status == 6) {
            $status_id = 6;
        } else {
            $status_id = $progress > 0 ? 4 : 3;
            if ($today > $start_date) {
                if ($today < $end_date) {
                    $status_id = 4;
                    if ($progress == 0) {
                        $status_id = 11;
                    }
                } else if ($today > $end_date) {
                    $status_id = 11;
                }
            }
        }

        $status_id = $status == 5 ? 5 : $status_id;
        return    get_status($status_id);
    }



    function get_progress($progress)
    {
        $project_progress = '
        <div class="progress" style="height:20px; font-size:10px; color:black">
            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                ' . $progress . '%
            </div>
        </div>';

        if ($progress == 100) {
            $project_progress = '
            <div class="progress" style="height:20px; font-size:10px; color:black">
                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                ' . $progress . '%
                </div>
            </div>';
        }
        return $project_progress;
    }


    function calculate_project_progress($projid, $implimentation_type)
    {
        global $db;
        $direct_cost = 0;
        if ($implimentation_type == 1) {
            $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=1 ");
            $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
            $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
            $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
        } else {
            $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_tender_details WHERE projid =:projid ");
            $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
            $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
            $direct_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
        }

        $query_rsTask_Start_Dates = $db->prepare("SELECT * FROM tbl_program_of_works WHERE projid=:projid");
        $query_rsTask_Start_Dates->execute(array(':projid' => $projid));
        $totalRows_rsTask_Start_Dates = $query_rsTask_Start_Dates->rowCount();

        $progress = 0;
        $cost_used = 0;
        $project_complete = [];
        if ($totalRows_rsTask_Start_Dates > 0) {
            while ($Rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch()) {
                $subtask_id = $Rows_rsTask_Start_Dates['subtask_id'];
                $site_id = $Rows_rsTask_Start_Dates['site_id'];
                $complete = $Rows_rsTask_Start_Dates['complete'];

                $query_rsOther_cost_plan_budget =  $db->prepare("SELECT unit_cost , units_no FROM tbl_project_direct_cost_plan WHERE projid =:projid AND site_id=:site_id AND subtask_id=:subtask_id AND cost_type=1 ");
                $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":site_id" => $site_id, ":subtask_id" => $subtask_id));
                $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();

                if ($implimentation_type == 2) {
                    $query_rsOther_cost_plan_budget =  $db->prepare("SELECT unit_cost , units_no FROM tbl_project_tender_details WHERE projid =:projid AND site_id=:site_id AND subtask_id=:subtask_id");
                    $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":site_id" => $site_id, ":subtask_id" => $subtask_id));
                    $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                }

                $units = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['units_no'] : 0;
                $unit_cost = $row_rsOther_cost_plan_budget ? $row_rsOther_cost_plan_budget['unit_cost'] : 0;
                $cost = $units * $unit_cost;
                $cost_used += $units * $unit_cost;
                $percentage = 100;

                if ($complete == 0) {
                    $project_complete[] = false;
                    $query_rsPercentage =  $db->prepare("SELECT SUM(achieved)  as achieved FROM tbl_project_monitoring_checklist_score WHERE projid =:projid  AND site_id=:site_id AND subtask_id=:subtask_id");
                    $query_rsPercentage->execute(array(":projid" => $projid, ":site_id" => $site_id, ":subtask_id" => $subtask_id));
                    $row_rsPercentage = $query_rsPercentage->fetch();
                    $sub_percentage = $row_rsPercentage['achieved'] != null ?  ($row_rsPercentage['achieved'] / $units) * 100 : 0;
                    $percentage = $sub_percentage >= 100 ? 99 : $sub_percentage;
                }

                $progress += $cost > 0 && $direct_cost > 0 ? $cost / $direct_cost * $percentage : 0;
            }
            $progress =  !in_array(false, $project_complete) ? 100 : $progress;
        }

        return $progress;
    }

    $query_user =  $db->prepare("SELECT p.*, u.password as password FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid =:user_id");
    $query_user->execute(array(":user_id" => $user_name));
    $row_rsUser = $query_user->fetch();
    $printedby = $row_rsUser["title"] . "." . $row_rsUser["fullname"];

    $query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
    $query_company->execute();
    $row_company = $query_company->fetch();

    $filter = "";

    if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
        $prjsector = $_GET['sector'];
        $prjdept = $_GET['department'];
        $projfyfrom = $_GET['projfyfrom'];
        $projfyto = $_GET['projfyto'];

        $sector = !empty($_GET['sector']) ? $_GET['sector'] : null;;
        $dept = !empty($_GET['department']) ? $_GET['department'] : null;
        $fyfrom = !empty($_GET['projfyfrom']) ? $_GET['projfyfrom'] : null;
        $fyto = !empty($_GET['projfyto']) ? $_GET['projfyto'] : null;
        $filter = message($get = 1, $sector, $dept, $fyfrom, $fyto);


        if (!empty($prjsector) && empty($prjdept) && empty($projfyfrom) && empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and p.deleted='0' ORDER BY `projid` ASC");
            $sql->execute(array(":prjsector" => $prjsector));
        } elseif (!empty($prjsector) && !empty($prjdept) && empty($projfyfrom) && empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and g.projdept=:prjdept and p.deleted='0' ORDER BY `projid` ASC");
            $sql->execute(array(":prjsector" => $prjsector, ":prjdept" => $prjdept));
        } elseif (empty($prjsector) && empty($prjdept) && !empty($projfyfrom) && empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM tbl_projects WHERE projstatus > 9 and projfscyear >= :projfyfrom and deleted='0' ORDER BY `projid` ASC");
            $sql->execute(array(":projfyfrom" => $projfyfrom));
        } elseif (empty($prjsector) && empty($prjdept) && !empty($projfyfrom) && !empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM tbl_projects WHERE projstatus > 9 and (projfscyear >= :projfyfrom and projfscyear <=:projfyto) and deleted='0' ORDER BY `projid` ASC");
            $sql->execute(array(":projfyfrom" => $projfyfrom, ":projfyto" => $projfyto));
        } elseif (!empty($prjsector) && empty($prjdept) && !empty($projfyfrom) && empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and p.projfscyear >= :projfyfrom and p.deleted='0' ORDER BY `projid` ASC");
            $sql->execute(array(":prjsector" => $prjsector, ":projfyfrom" => $projfyfrom));
        } elseif (!empty($prjsector) && !empty($prjdept) && !empty($projfyfrom) && empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and g.projdept=:prjdept and p.projfscyear >= :projfyfrom and p.deleted='0' ORDER BY `projid` ASC");
            $sql->execute(array(":prjsector" => $prjsector, ":prjdept" => $prjdept, ":projfyfrom" => $projfyfrom));
        } elseif (!empty($prjsector) && empty($prjdept) && !empty($projfyfrom) && !empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and (projfscyear >= :projfyfrom and projfscyear <= :projfyto) and p.deleted='0' ORDER BY `projid` ASC");
            $sql->execute(array(":prjsector" => $prjsector, ":projfyfrom" => $projfyfrom, ":projfyto" => $projfyto));
        } elseif (!empty($prjsector) && !empty($prjdept) && !empty($projfyfrom) && !empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and g.projdept=:prjdept and (projfscyear >= :projfyfrom and projfscyear <=:projfyto) and p.deleted='0' ORDER BY `projid` ASC");
            $sql->execute(array(":prjsector" => $prjsector, ":prjdept" => $prjdept, ":projfyfrom" => $projfyfrom, ":projfyto" => $projfyto));
        } elseif (empty($prjsector) && empty($prjdept) && empty($projfyfrom) && empty($projfyto)) {
            $sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE projstatus > 9 ORDER BY `projid` ASC");
            $sql->execute();
        }
    } else {
        $sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE projstatus > 9 ORDER BY `projid` ASC");
        $sql->execute();
    }

    $rows_count = $sql->rowCount();

    function statuses($projstatus)
    {
        global $db;
        $status = $db->prepare("SELECT * FROM `tbl_status` WHERE statusid=:statusid LIMIT 1");
        $status->execute(array(":statusid" => $projstatus));
        $rowstatus = $status->fetch();
        $status = $rowstatus["statusname"];

        $active = '';
        if ($projstatus == 3) {
            $active = '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $status . '</button>';
        } else if ($projstatus == 4) {
            $active = '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $status . '</button>';
        } else if ($projstatus == 11) {
            $active = '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $status . '</button>';
        } else if ($projstatus == 5) {
            $active = '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $status . '</button>';
        } else if ($projstatus == 1) {
            $active = '<button type="button" class="btn bg-grey waves-effect" style="width:100%">' . $status . '</button>';
        } else if ($projstatus == 2) {
            $active = '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $status . '</button>';
        } else if ($projstatus == 6) {
            $active = '<button type="button" class="btn bg-pink waves-effect" style="width:100%">' . $status . '</button>';
        }
        return $active;
    }

    function progress($percentage)
    {
        $percent = '';
        if ($percentage < 100) {
            $percent = '
          <div class="progress" style="height:20px; font-size:10px; color:black">
              <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percentage . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentage . '%; height:20px; font-size:10px; color:black">
                  ' . $percentage . '%
              </div>
          </div>';
        } elseif ($percentage == 100) {
            $percent = '
          <div class="progress" style="height:20px; font-size:10px; color:black">
              <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percentage . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentage . '%; height:20px; font-size:10px; color:black">
              ' . $percentage . '%
              </div>
          </div>';
        }
        return $percent;
    }

    $logo = 'logo.jpg';
    $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
    $mpdf->SetWatermarkImage($logo);
    $mpdf->showWatermarkImage = true;
    // $mpdf->SetProtection(array(), 'UserPassword', 'password');

    $mpdf->AddPage('l');
    $mpdf->WriteHTML('
      <div style="text-align: center;">
         <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
         <h2 style="" >' . $row_company["company_name"] . '</h2>
         <br/>
         <hr/>
         <h3 style="margin-top:10px;" >PROJECTS IMPLEMENTATION STATUS REPORT</h3>
         <hr/>
         <div style="margin-top:80px;" >
            <address>
               <h5>The County Treasury ' . $row_company["postal_address"] . ', KENYA </h5>
               <h5>Email: ' . $row_company["email_address"] . ' </h5>
               <h5>Website: ' . $row_company["domain_address"] . '</h5>
            </address>
            <h4>' . date('d M Y') . '</h4>
         </div>
      </div>
      ');

    $mpdf->SetHTMLHeader(
        '
      <div style="text-align: right;">
        <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
         <p><i> Projects Implementation Status Report</i></p>
      </div>'
    );

    $mpdf->AddPage('L');


    $body = $filter . '
    <table class="table table-bordered table-striped table-hover" id="manageItemTable">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="38%">Project Name</th>
            <th width="15%">Ward</th>
            <th width="12%">Start/End&nbsp;Date</th>
            <th width="10%">Cost</th>
            <th width="10%">Expenditure</th>
            <th width="10%">Status & Progress</th>
            <!--<th width="8%">Report</th>-->
        </tr>
    </thead>
    <tbody>
    ';

    if ($rows_count > 0) {
        // $row = $result->fetch_array();
        $active = "";
        $sn = 0;
        while ($row = $sql->fetch()) {
            $sn++;
            $itemId = $row['projid'];
            $progid = $row['progid'];

            $queryobj = $db->prepare("SELECT objective FROM `tbl_programs` g inner join tbl_strategic_plan_objectives o on o.id=g.strategic_obj WHERE progid=:progid");
            $queryobj->execute(array(":progid" => $progid));
            $rowobj = $queryobj->fetch();
            $objective = $rowobj["objective"];

            $query_rsBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid ='$itemId'");
            $query_rsBudget->execute();
            $row_rsBudget = $query_rsBudget->fetch();
            $totalRows_rsBudget = $query_rsBudget->rowCount();
            $projbudget = $row_rsBudget['budget'];

            $projname = $row["projname"];
            $projbudget = $row["projcost"];
            $budget = number_format($projbudget, 2);
            $projstatus = $row["projstatus"];
            $projstartdate = $row["projstartdate"];
            $projenddate = $row["projenddate"];
            $projstate = explode(",", $row['projlga']);

            $query_projectexp = $db->prepare("SELECT SUM(amount_requested) AS exp FROM `tbl_payments_request` WHERE status=3 AND projid=:projid");
            $query_projectexp->execute(array(":projid" => $itemId));
            $row_projectexp = $query_projectexp->fetch();
            $totalRows_projectexp = $query_projectexp->rowCount();
            $projectcost = 0;
            if ($totalRows_projectexp > 0) {
                $projectcost = $row_projectexp["exp"];
            }

            $status = $db->prepare("SELECT * FROM `tbl_status` WHERE statusid=:statusid LIMIT 1");
            $status->execute(array(":statusid" => $projstatus));
            $rowstatus = $status->fetch();
            $status = $rowstatus["statusname"];
            //$progress = 45;

            $states = array();
            for ($i = 0; $i < count($projstate); $i++) {
                $state = $db->prepare("SELECT * FROM `tbl_state` WHERE id=:stateid LIMIT 1");
                $state->execute(array(":stateid" => $projstate[$i]));
                $rowstate = $state->fetch();
                $state = $rowstate["state"];
                array_push($states, $state);
            }

            // project percentage progress
            $progress = number_format(calculate_project_progress($itemId, $implementation), 2);
            $percent2 = get_progress($progress);



            $query_projremarks = $db->prepare("SELECT * FROM `tbl_projects_performance_report_remarks` WHERE projid=:projid LIMIT 1");
            $query_projremarks->execute(array(":projid" => $itemId));
            $totalRows_projremarks = $query_projremarks->rowCount();

            $body .= '
            <tr class="projects">
                <td>' . $sn . '</td>
                <td>' . $projname . '</td>
                <td>' . implode(", ", $states) . '</td>
                <td>' . $projstartdate . '/' . $projenddate . '</td>
                <td>' . number_format($projbudget, 2) . '</td>
                <td>' . number_format($projectcost, 2) . '</td>
                <td>' . get_status($projstatus) . '<br>' . $percent2 . '</td>
            </tr>
            <tr class="">
                <td class="bg-grey text-center"></td>
                <td colspan="6"><strong>Project Objective: </strong>' . $objective . '</td>
            </tr>
            <tr class="">
                <th class="bg-grey text-center"></th>
                <th colspan="2">Activity</th>
                <th colspan="3">Start and End Dates</th>
                <th>Status & Progress</th>
            </tr>';


            $queryactivities = $db->prepare("SELECT * FROM `tbl_task`  WHERE projid=:projid");
            $queryactivities->execute(array(":projid" => $itemId));
            $nm = 0;
            while ($rowact = $queryactivities->fetch()) {
                $nm++;
                $activity = $rowact["task"];
                $subtask_id = $rowact["tkid"];

                $querytaskstatus = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM `tbl_program_of_works` WHERE subtask_id=:subtask_id");
                $querytaskstatus->execute(array(":subtask_id" => $subtask_id));
                $rowtaskstatus = $querytaskstatus->fetch();
                $startdate = $rowtaskstatus ? date('d M Y', strtotime($rowtaskstatus["start_date"])) : "";
                $enddate = $rowtaskstatus ? date('d M Y', strtotime($rowtaskstatus["end_date"])) : "";

                $query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where  subtask_id=:subtask_id");
                $query_Site_score->execute(array(":subtask_id" => $subtask_id));
                $row_site_score = $query_Site_score->fetch();
                $units_no = ($row_site_score['achieved'] != null) ? $row_site_score['achieved'] : 0;

                $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(units_no) as units_no FROM tbl_project_direct_cost_plan WHERE subtask_id=:subtask_id");
                $query_rsOther_cost_plan_budget->execute(array(":subtask_id" => $subtask_id));
                $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                $target_units = !is_null($row_rsOther_cost_plan_budget['units_no']) ? $row_rsOther_cost_plan_budget['units_no'] : 0;
                $progress = number_format(($units_no / $target_units) * 100);

                $query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE  subtask_id=:subtask_id AND complete=0 ");
                $query_rsPlan->execute(array(':subtask_id' => $subtask_id));
                $totalRows_plan = $query_rsPlan->rowCount();
                $status = $totalRows_plan > 0 ? 0 : 5;

                $body .= '
                <tr class="">
                    <td class="bg-grey text-center">' . $sn . '.' . $nm . '</td>
                    <td colspan="2">' . $activity . '</td>
                    <td colspan="3">' . $startdate . ' AND ' . $enddate . '</td>
                    <td>' . get_subtask_status($progress, $startdate, $enddate, $status, $projstatus) . '<br>' . get_progress($progress) . '</td>
                </tr>';
            }

            $body .= '
            <tr class="">
                <td class="bg-grey text-center"></td>
                <td colspan="6" class="bg-grey">';
            if ($totalRows_projremarks > 0) {
                $row_projremarks = $query_projremarks->fetch();
                $body .= '<strong>Project Remarks: </strong>' . $row_projremarks["remarks"];
            } else {
                $body .= '<strong>Project Remarks: Data not available!</strong>';
            }
            $body .= '</td>
            </tr>';
        }
    } else {
        $body .= '
        <tr class="projects">
            <td class="text-center mb-0">
            </td>
            <td colspan="6">No records found</td>
        </tr>';
    }

    $body .= '</table>';

    $stylesheet = file_get_contents('bootstrap.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($body, \Mpdf\HTMLParserMode::HTML_BODY);
    //  $mpdf->WriteHTML($body);
    $mpdf->WriteHTML('<h5 style="color:green">Printed By: ' . $printedby . '</h5>');
    $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
    $mpdf->Output();
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
}
