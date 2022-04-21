

<?php
$stplan = (isset($_GET['plan'])) ? $_GET['plan'] : header("Location: view-strategic-plans.php");

// Require composer autoload
require_once __DIR__ . '../vendor/autoload.php';
// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf();
$mpdf->setAutoTopMargin = 'stretch';

$stplane = base64_encode($stplan);
require 'functions/strategicplan.php';
if (!$strategicPlan) {
    // redirect back to strategic plan
    header("Location: view-strategic-plans.php");
}

$plan = $strategicPlan["plan"];
$vision = $strategicPlan["vision"];
$mission = $strategicPlan["mission"];
$years = $strategicPlan["years"];
$mission = $strategicPlan["mission"];
$datecreated = $strategicPlan["date_created"];
$start_year = $strategicPlan["starting_year"] - 1;
$end_year = $strategicPlan["starting_year"] - 1;
$query_sector = $db->prepare("SELECT indicator_sector,stid,sector FROM tbl_indicator i inner join tbl_sectors s on s.stid=i.indicator_sector WHERE i.indicator_category = 'Output' AND s.deleted='0' GROUP BY stid ORDER BY stid");
$query_sector->execute();
$totalRows_sector = $query_sector->rowCount();

$logo = "download.jpg";
$mpdf->AddPage('l');
$mpdf->WriteHTML('
<div style="text-align: center;">
   <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
   <h2 style="" >COUNTY GOVERNMENT OF UASHIN GISHU</h2>
   <br/>
   <hr/>
   <h3 style="margin-top:10px;" >COUNTY INTERGRATED DEVELOPMENT PLAN (CIDP)</h3>
   <h3 style="margin-top:10px;" >' . $start_year . '-' . $end_year . '</h3>
   <hr/>
   <div style="margin-top:80px;" >
      <address>
         <h5>The County Treasury P. O. Box 40-30100 ELDORET, KENYA </h5>
         <h5>Email: info@uasingishu.go.ke </h5>
         <h5>Website: www.uasingishu.go.ke </h5>
      </address>
      <h4>JUNE 2018</h4>
   </div>
</div>
');

$mpdf->SetHTMLHeader('
<div style="text-align: right;">
  <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
   <p><i>County Integrated Development Plan (CIDP ' . $start_year . '-' . $end_year . ')</i></p>
</div>'
);

$mpdf->AddPage('L');

$mpdf->WriteHTML('
<h4 style="color:green">Vision: </h4>
<p>' . $vision . '</p>
<h4 style="color:green">Mission:</h4>
<p>' . $mission . '</p>');

$mpdf->AddPage('L');

$body = '';
$sn = 0;
if ($totalRows_sector > 0) {
    while ($sector_rows = $query_sector->fetch()) {
        $department = $sector_rows["sector"];
        $stid = $sector_rows["stid"];

        $query_ind = $db->prepare("SELECT indid, indicator_name FROM tbl_indicator WHERE indicator_sector='$stid' AND indicator_category = 'Output' AND baseline=1");
        $query_ind->execute();
        $totalRows_ind = $query_ind->rowCount();

        $sn++;
        $financial_years = '';
        $s_year = $start_year;
        $output_header = '';
        for ($j = 0; $j < $years; $j++) {
            $e_year = $s_year + 1;
            $financial_years .= '<th colspan="3" style="color:white;">' . $s_year . '/' . $e_year . '</th>';
            $output_header .= '
         <th style="color:white;">Target </th>
         <th style="color:white;">Achieved </th>
         <th style="color:white;">Rate </th>';
            $s_year++;
        }
        $body .= '
   <style>
      table, td, th {
         border-collapse: collapse;
         border: 1px solid orange;
      }
   </style>
   <h4>Department: <small> ' . $sn . '). ' . $department . '</small></h4>
   <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="border-style:1px solid red">
      <thead>
         <tr style="color:white; background-color:#0074D9;">
            <th colspan="" rowspan="2" style="color:white;">#</th>
            <th colspan="" rowspan="2" style="color:white;">Output&nbsp;Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th colspan="" rowspan="2" style="color:white;">Base&nbsp;Year&nbsp;:&nbsp;Base&nbsp;Value</th>
            ' . $financial_years . '
            <th colspan="1" rowspan="2" style="color:white;">Total&nbsp;Budget</th>
         </tr>
         <tr class="bg-light-blue" style="background-color:#0074D9; color:white">
            ' . $output_header . '
         </tr>
      </thead>
      <tbody style="background-color:white">';
        $mn = 0;
        if ($totalRows_ind > 0) {
            while ($row_ind = $query_ind->fetch()) {
                $nm = $nm + 1;
                $indicator = $row_ind["indicator_name"];
                $indid = $row_ind["indid"];
                $target = 0;
                $achieved = 0;
                $rate = 0;
                $basevalue = 0;
                $baseyear = "N/A";

                $query_indbaseyr = $db->prepare("SELECT yr FROM tbl_indicator_baseline_years b inner join  tbl_fiscal_year y on y.id=b.year WHERE indid='$indid'");
                $query_indbaseyr->execute();
                $totalRows_baseyr = $query_indbaseyr->rowCount();
                if ($totalRows_baseyr > 0) {
                    $row_indbaseyr = $query_indbaseyr->fetch();
                    $baseyear = $row_indbaseyr["yr"];
                }

                $query_indbaseline = $db->prepare("SELECT SUM(value) as baseline FROM tbl_indicator_output_baseline_values WHERE indid='$indid'");
                $query_indbaseline->execute();
                $row_indbaseline = $query_indbaseline->fetch();
                $basevalue = $row_indbaseline["baseline"];
                if ($basevalue > 0) {
                    $basevalue = number_format($basevalue);
                } else {
                    $basevalue = 0;
                }

                $budget = 0;
                $query_indbudget = $db->prepare("SELECT budget FROM tbl_strategic_plan_op_indicator_budget WHERE indid='$indid' AND spid='$stplan'");
                $query_indbudget->execute();
                $totalRows_indbudget = $query_indbudget->rowCount();
                if ($totalRows_indbudget > 0) {
                    $row_indbudget = $query_indbudget->fetch();
                    $budget = number_format($row_indbudget["budget"]);
                }

                $q_year = $start_year;
                $base_value = 1000;
                $total_budget = 1000000000;

                $mn++;
                $outputs = '';
                for ($q = 0; $q < $years; $q++) {
                    $year = $spfnyear + $k;
                    $targetraw = get_strategic_plan_yearly_target($indid, $stplan, $year);
                    $achievedraw = get_strategic_plan_yearly_achieved($indid, $stplan, $year);

                    if (!empty($achievedraw)) {
                        $achieved = get_strategic_plan_yearly_achieved($indid, $stplan, $year);
                    }

                    if (!empty($targetraw)) {
                        $target = get_strategic_plan_yearly_target($indid, $stplan, $year);
                        $rate = $achieved / $target;
                    }
                    $outputs .= '
                  <td>' . number_format($target, 2) . ' </td>
                  <td>' . number_format($achieved, 2) . ' </td>
                  <td>' . number_format($rate, 2) . ' </td>
                  ';
                    $q_year++;
                }

                $body .= '
                  <tr>
                     <td> ' . $sn . '.' . $mn . '</td>
                     <td>Output Testing indicator ' . $mn . ' </td>
                     <td>' . number_format($base_value, 2) . ' </td>
                     ' . $outputs . '
                     <td>' . number_format($total_budget, 2) . '</td>
                  </tr>';
            }
        } else {
            $colspan = 4 + ($years * 3);
            $body .=
                '<tr style="background-color:#01FF70">
               <td class="bg-lime" colspan=" ' . $colspan . '" align="left" style="padding-left:20px">
                  <font color="red"><b>Sorry no record found!!</b></font>
               </td>
            </tr>';
        }
        $body .= '
      </tbody>
   </table>';
    }
}

$mpdf->WriteHTML($body);
$mpdf->SetFooter('Uasin Gishu County {PAGENO}');
$mpdf->Output();
