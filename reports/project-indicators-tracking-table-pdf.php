<?php
//include_once 'projtrac-dashboard/resource/session.php';

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

    function message($get = null, $sector = null, $dept = null, $year = null, $projfyto=null)
    {
        $filter = '<h4 style="color:green" align="left">Filters </h4>';
        if ($get) {
            if ($year) {
                $fsc_year = get_financial_year($year);
                $filter .= '<h4>From Financial Year:<small>' . $fsc_year . '</small></h4>';
                if($projfyto){ 
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

    $filter = "";

    if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
        $prjsector = $_GET['sector'];
        $prjdept = $_GET['department'];
        $projfyfrom = $_GET['projfyfrom'];
        $projfyto = $_GET['projfyto'];

        $sector =!empty($_GET['sector']) ? $_GET['sector'] : null;;
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
    $mpdf->SetProtection(array(), 'UserPassword', 'password');

    $mpdf->AddPage('l');
    $mpdf->WriteHTML('
      <div style="text-align: center;">
         <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
         <h2 style="" >COUNTY GOVERNMENT OF UASIN GISHU</h2>
         <br/>
         <hr/>
         <h3 style="margin-top:10px;" >PROJECTS PERFORMANCE REPORT</h3>
         <hr/>
         <div style="margin-top:80px;" >
            <address>
               <h5>The County Treasury P. O. Box 40-30100 ELDORET, KENYA </h5>
               <h5>Email: info@uasingishu.go.ke </h5>
               <h5>Website: www.uasingishu.go.ke </h5>
            </address>
            <h4>' . date('d M Y') . '</h4>
         </div>
      </div>
      ');

    $mpdf->SetHTMLHeader(
        '
      <div style="text-align: right;">
        <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
         <p><i> Projects Performance Report</i></p>
      </div>'
    );

    $mpdf->AddPage('L');
    $body = $filter.'
    <table class="table table-bordered table-striped table-hover" id="manageItemTable">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="38%">Project Name</th>
            <th width="15%">Location</th>
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
            $projstate = explode(",", $row['projstate']);

            $query_projectexp = $db->prepare("SELECT SUM(amountpaid) AS exp FROM `tbl_payments_request` r inner join `tbl_payments_disbursed` d on d.reqid=r.id WHERE r.projid=:projid");
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
            $query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
            $query_rsMlsProg->execute(array(":projid" => $itemId));
            $row_rsMlsProg = $query_rsMlsProg->fetch();
            $percent2 = 0;
            if ($row_rsMlsProg["mlprogress"] > 0 && $row_rsMlsProg["nmb"] > 0) {
                $prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];
                $percent2 = round($prjprogress, 2);
            }

            $queryactivities = $db->prepare("SELECT * FROM `tbl_task` WHERE projid=:projid");
            $queryactivities->execute(array(":projid" => $itemId));

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
         <td>' . statuses($projstatus) . '<br>' . progress($percent2) . '</td>
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
      </tr>

      ';

            $nm = 0;
            while ($rowact = $queryactivities->fetch()) {
                $nm++;
                $activity = $rowact["task"];
                $startdate = $rowact["sdate"];
                $enddate = $rowact["edate"];
                $statusid = $rowact["status"];
                $progress = $rowact["progress"];
                $querytaskstatus = $db->prepare("SELECT statusname FROM `tbl_task_status` WHERE statusid=:statusid");
                $querytaskstatus->execute(array(":statusid" => $statusid));
                $rowtaskstatus = $querytaskstatus->fetch();
                $taskstatus = $rowtaskstatus["statusname"];
                $body .= '
           <tr class="">
              <td class="bg-grey text-center">' . $sn . '.' . $nm . '</td>
              <td colspan="2">' . $activity . '</td>
              <td colspan="3">' . $startdate . ' AND ' . $enddate . '</td>
              <td>' . statuses($statusid) . '<br>' . progress($progress) . '</td>
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
                $body .= '<button type="button" data-toggle="modal" data-target="#remarksItemModal" id="remarksItemModalBtn"  class="btn btn-success btn-sm" onclick=remarks("' . $itemId . '")>
            <i class="glyphicon glyphicon-file"></i><strong> Add Project Remarks</strong>
            </button>';
            }
            $body .= '</td></tr>';
        }
    } else {
        $body .= '
   <tr class="projects">
       <td class="text-center mb-0">
       </td>
       <td colspan="6">No records found</td>
   </tr>';
    }

    $body .= '</></table>';

    $stylesheet = file_get_contents('bootstrap.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($body, \Mpdf\HTMLParserMode::HTML_BODY);
    //  $mpdf->WriteHTML($body);
    $mpdf->WriteHTML('<h4 style="color:green">Printed By: </h4>');
    $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
    $mpdf->Output();
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
}
