<?php
session_start();
$user_name = $_SESSION['MM_Username'];

$stplan = (isset($_GET['plan'])) ? $_GET['plan'] : header("Location: view-strategic-plans.php");

//include_once 'projtrac-dashboard/resource/session.php';

include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php';
require '../functions/strategicplan.php';
 
try {
	$query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
	$query_company->execute(array(":stid" => $stid));
	$row_company = $query_company->fetch();
	
    $query_user =  $db->prepare("SELECT p.*, u.password as password FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid =:user_id");
    $query_user->execute(array(":user_id" => $user_name));
    $row_rsUser = $query_user->fetch();
	$printedby = $row_rsUser["title"].".".$row_rsUser["fullname"];
	
    $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE id='$stplan' and current_plan=1 LIMIT 1");
    $query_rsStrategicPlan->execute();
    $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
    $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();

	/* $spyears = $row_stratplanyr["years"];
	$spfnyear = $row_stratplanyr["starting_year"]; */
	
    if ($totalRows_rsStrategicPlan == 0) {
        // redirect back to strategic plan
        header("Location: view-strategic-plans.php");
    }

    $plan = $row_rsStrategicPlan["plan"];
    $vision = $row_rsStrategicPlan["vision"];
    $mission = $row_rsStrategicPlan["mission"];
    $years = $row_rsStrategicPlan["years"];
    $mission = $row_rsStrategicPlan["mission"];
    $datecreated = $row_rsStrategicPlan["date_created"];
    $start_year = $row_rsStrategicPlan["starting_year"];
    $end_year = $start_year + $years;
    $logo = 'logo.jpg';
    $query_sector = $db->prepare("SELECT indicator_sector,stid,sector FROM tbl_indicator i inner join tbl_sectors s on s.stid=i.indicator_sector WHERE i.indicator_category = 'Output' AND s.deleted='0' GROUP BY stid ORDER BY stid");
    $query_sector->execute();
    $totalRows_sector = $query_sector->rowCount();
    $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);

    $mpdf->SetWatermarkImage($logo);
    $mpdf->showWatermarkImage = true;
    $mpdf->SetProtection(array(), 'UserPassword', 'password');

    $mpdf->AddPage('l');

    $mpdf->WriteHTML('
      <div style="text-align: center;">
         <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
         <h2 style="" >'.$row_company["company_name"].'</h2>
         <br/>
         <hr/>
         <h3 style="margin-top:10px;" >COUNTY INTERGRATED DEVELOPMENT PLAN (CIDP)</h3>
         <h3 style="margin-top:10px;" >' . $start_year . '-' . $end_year . '</h3>
         <hr/>
         <div style="margin-top:80px;" >
            <address>
               <h5>The County Treasury '.$row_company["postal_address"].', KENYA </h5>
               <h5>Email: '.$row_company["email_address"].' </h5>
               <h5>Website: '.$row_company["domain_address"].' </h5>
            </address>
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
         <p>' . $mission . '</p>'
    );
    $mpdf->AddPage('L');

    $body = "";
    if ($totalRows_sector > 0) {
        $sn = 0;
        while ($sector_rows = $query_sector->fetch()) {
            $department = $sector_rows["sector"];
            $stid = $sector_rows["stid"];

            $query_ind = $db->prepare("SELECT indid, indicator_name, unit FROM tbl_indicator i left join tbl_measurement_units u  on u.id=i.indicator_unit WHERE indicator_sector='$stid' AND indicator_category = 'Output' AND baseline=1");
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
                $nm = 0;
                while ($row_ind = $query_ind->fetch()) {
                    $nm = $nm + 1;
                    $indicator = $row_ind["indicator_name"];
                    $indid = $row_ind["indid"];
                    $unit = $row_ind["unit"];
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
					$query_indbudget =  $db->prepare("SELECT SUM(d.budget) AS budget FROM tbl_progdetails d left join tbl_programs g on g.progid=d.progid WHERE indicator='$indid' AND strategic_plan='$stplan'");
																					
                    //$query_indbudget = $db->prepare("SELECT budget FROM tbl_strategic_plan_op_indicator_budget WHERE indid='$indid' AND spid='$stplan'");
                    $query_indbudget->execute();
                    $totalRows_indbudget = $query_indbudget->rowCount();
                    if ($totalRows_indbudget > 0) {
                        $row_indbudget = $query_indbudget->fetch();
                        $budget = number_format($row_indbudget["budget"]);
                    }

                    $q_year = $start_year;
                    $mn++;
                    $outputs = '';
                    for ($q = 0; $q < $years; $q++) {
						$year = $start_year + $q;
						$targetraw = get_strategic_plan_yearly_target($indid, $year);
						$achievedraw = get_strategic_plan_yearly_achieved($indid, $year);
						$basevalue = get_strategic_plan_yearly_baseline($indid, $year);
						$achieved = 0;
						$target = 0;
						$rate = 0;
						
						if (!empty($achievedraw)) {
							$achieved = get_strategic_plan_yearly_achieved($indid, $year);
						}
						
						if (!empty($targetraw)) {
							$target = get_strategic_plan_yearly_target($indid, $year);
							$rate  = ($achieved / $target)*100;
						}
                        
                        $outputs .= '
                          <td>' . $target . ' </td>
                          <td>' . $achieved . ' </td>
                          <td>' . number_format($rate, 2) . ' </td>
                          ';
                        $q_year++;
						
						unset($target);
						unset($achieved);
						unset($rate);
                    }

                    $body .= '
                          <tr>
                             <td> ' . $sn . '.' . $mn . '</td>
                             <td>' . $unit . ' of ' . $indicator . ' </td>
                             <td>' . $basevalue . ' </td>
                             ' . $outputs . '
                             <td>' . $budget . '</td>
                          </tr>';
                }

            } else {
                $colspan = 4 + ($years * 3);
                $body .=
                    '<tr>
                        <td class="bg-lime" colspan=" ' . $colspan . '" align="center" style="padding-left:20px">
                           <font color="red"><b>Sorry no record found!!</b></font>
                        </td>
                     </tr>';
            }
            $body .= '
            </tbody>
         </table>';

        }
    } else {
        $body .= "No depatments Found !!!";
    }
    $mpdf->WriteHTML($body);
    $mpdf->WriteHTML('<h5 style="color:green">Printed By: '.$printedby.'</h5>'); 
    $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
    $mpdf->Output();

} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
}
