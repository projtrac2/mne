<?php
//include_once 'projtrac-dashboard/resource/session.php';
session_start();
$user_name = $_SESSION['MM_Username'];

include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php';

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

    function state($stid = null)
    {
        global $db;
        if ($stid) {
            $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id='$stid'");
            $query_rsComm->execute();
            $row_rsComm = $query_rsComm->fetch();
            $totalRows_rsComm = $query_rsComm->rowCount();
            if ($totalRows_rsComm > 0) {
                return $row_rsComm['state'];
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

    function message($get = null, $sector = null, $dept = null, $year = null)
    {
        $filter = '';
        if ($get) {
            if ($year) {
                $fsc_year = get_financial_year($year);
                $filter .= '<h4>Financial Year: <small>' . $fsc_year . '</small></h4>';
            }
            
            if ($sector != null) {
                $sect = get_department($sector);
                $filter .= '<h4>Department: <small>' . $sect . '</small></h4>';
                if ($dept) {
                    $department = get_department($dept);
                    $filter .= '<h4>Section: <small>' . $department . '</small> </h4>';
                }
            }
        } else {
			
			$currentYear = date('Y');
			$month =  date('m');

			if ($month  < 7) {
				$syear =  $currentYear - 1;
				$eyear =  $currentYear;
			} else {
				$syear = $currentYear;
				$eyear =  $currentYear + 1;
			}
			$fsc_year = $syear."/".$eyear;
            $filter .= '<h4>Financial Year: <small>' . $fsc_year . '</small></h4>
						<h4>Department: <small>All departments</small></h4>
						<h4>Section: <small>All sections</small> </h4>';
        }
        return $filter;
    }
	$query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
	$query_company->execute(array(":stid" => $stid));
	$row_company = $query_company->fetch();

    $query_user =  $db->prepare("SELECT p.*, u.password as password FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid =:user_id");
    $query_user->execute(array(":user_id" => $user_name));
    $row_rsUser = $query_user->fetch();
	$printedby = $row_rsUser["title"].".".$row_rsUser["fullname"]

    $yr = date("Y");
    $mnth = date("m");
    $startmnth = 07;
    $endmnth = 06;
    $startyear = "";
    if ($mnth >= 7 && $mnth <= 12) {
        $startyear = $yr;
        $endyear = $yr + 1;
    } elseif ($mnth >= 1 && $mnth <= 6) {
        $startyear = $yr - 1;
        $endyear = $yr;
    }

    $filter = "";
    if (isset($_GET['sector']) ||isset($_GET['dept']) || isset($_GET['indfy'])) {
        $sector = !empty($_GET['sector']) ? $_GET['sector'] : null;;
        $dept = !empty($_GET['dept']) ? $_GET['dept'] : null;
        $fyid = !empty($_GET['indfy']) ? $_GET['indfy'] : null;
        $filter = message($get = 1, $sector, $dept, $fyid);
			
		$query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id =:year ");
		$query_rsFscYear->execute(array(":year"=>$fyid));
		$row_rsFscYear = $query_rsFscYear->fetch();
		$startyear = $row_rsFscYear['yr'];
		$endyear = $startyear + 1;

		if(!empty($sector) && !empty($fyid) && empty($dept)){
			$base_url = "indfy=$fyid&sector=$sector";
		}

		if(!empty($sector) && empty($dept) && empty($fyid)){
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector"=>$sector));
		} elseif(!empty($sector) && !empty($dept) && empty($fyid)){
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector and indicator_dept=:dept ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector"=>$sector, ":dept"=>$dept));
		} elseif(!empty($fyid) && empty($sector) && empty($dept)){
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
			$query_indicators->execute();
		} elseif(empty($_GET['indfy']) && empty($sector) && empty($dept)){	
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
			$query_indicators->execute();
		} elseif(!empty($sector) && !empty($dept) && !empty($fyid)){
			$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector and indicator_dept=:dept ORDER BY `indid` ASC");
			$query_indicators->execute(array(":sector"=>$sector, ":dept"=>$dept));
		}
	}else{
		$query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
		$query_indicators->execute();
        $filter = message($get = 0, $sector = 0, $dept = 0, $fyid = 0);
		
	 	$currentYear = date('Y');
		$month =  date('m');

		if ($month  < 7) {
			$syear =  $currentYear - 1;
			$eyear =  $currentYear;
		} else {
			$syear = $currentYear;
			$eyear =  $currentYear + 1;
		}
		$startyear = $syear;
		$endyear = $eyear;
	}

    $totalRows_indicators = $query_indicators->rowCount();

    $logo = 'logo.jpg';
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
        <h3 style="margin-top:10px;" > OUTPUT INDICATORS QUARTERLY PROGRESS REPORT</h3>
        <h3 style="margin-top:10px;" >' . $startyear . '-' . $endyear . '</h3>
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

    $mpdf->SetHTMLHeader(
        '
     <div style="text-align: right;">
       <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
        <p><i>County Integrated Development Plan (CIDP ' . $startyear . '-' . $endyear . ')</i></p>
     </div>'
    );

    $mpdf->AddPage('L');
    $body = '
    '.$filter.'
   <style>
      table, td, th {
         border-collapse: collapse;
         border: 1px solid orange;
      }
   </style>
   <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
      <thead>
         <tr class="bg-light-blue">
            <th colspan="" rowspan="2">#</th>
            <th colspan="" rowspan="2">Indicator </th>
            <th colspan="" rowspan="2">Baseline</th>
            <th colspan="" rowspan="2">Annual Target</th>';
                $years = $target_rows = $target_quarters = '';
                $target_quarters .= '
               <th colspan="3">Q1</th>
               <th colspan="3">Q2</th>
               <th colspan="3">Q3</th>
               <th colspan="3">Q4</th>';
                for ($jp = 0; $jp < 4; $jp++) {
                    $target_rows .= '
                  <th>Target</th>
                  <th>Achieved</th>
                  <th>Rate (%)</th>';
    }
    $body .= $target_quarters;
    $body .= '
         </tr>
         <tr class="bg-light-blue">';
    $body .= $target_rows;
    $body .= '</tr>
      </thead>
      <tbody>';

    $basedate = $startyear . "-06-30";
    $startq1 = $startyear . "-07-01";
    $endq1 = $endyear . "-09-30";
    $startq2 = $startyear . "-10-01";
    $endq2 = $endyear . "-12-31  ";
    $startq3 = $startyear . "-01-01";
    $endq3 = $endyear . "-03-31";
    $startq4 = $startyear . "-04-01";
    $endq4 = $endyear . "-06-30";

    $quarter_one_rate = "N/A";
    $quarter_two_rate = "N/A";
    $quarter_three_rate = "N/A";
    $quarter_four_rate = "N/A";
    $sn = 0;

    if ($totalRows_indicators > 0) {
        while ($row_indicators = $query_indicators->fetch()) {

            $initial_basevalue = 0;
            $actual_basevalue = 0;
            $annualtarget = 0;
            $basevalue = 0;
            $quarter_one_target = 0;
            $quarter_two_target = 0;
            $quarter_three_target = 0;
            $quarter_four_target = 0;
            $quarter_one_achived = 0;
            $quarter_two_achived = 0;
            $quarter_three_achived = 0;
            $quarter_four_achived = 0;
            $sn++;
            $indid = $row_indicators['indid'];
            $indicator = $row_indicators['indicator_name'];

			$query_indbasevalue_year = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid");
			$query_indbasevalue_year->execute(array(":indid" => $indid));
			$totalRows_indbasevalue_year = $query_indbasevalue_year->rowCount();

			if ($totalRows_indbasevalue_year > 0) {

				$query_initial_indbasevalue = $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid=:indid ");
				$query_initial_indbasevalue->execute(array(":indid" => $indid));
				$rows_initial_indbasevalue = $query_initial_indbasevalue->fetch();
				$totalRows_initial_indbasevalue = $query_initial_indbasevalue->rowCount();

				if ($totalRows_initial_indbasevalue > 0) {
					$initial_basevalue = $rows_initial_indbasevalue["basevalue"];
				}

				$query_actual_ind_value = $db->prepare("SELECT SUM(actualoutput) AS basevalue FROM tbl_monitoringoutput WHERE opid=:indid AND date_created < '$basedate'");
				$query_actual_ind_value->execute(array(":indid" => $indid));
				$rows_actual_ind_value = $query_actual_ind_value->fetch();
				$totalRows_actual_indbasevalue = $query_actual_ind_value->rowCount();

				if ($totalRows_actual_indbasevalue > 0) {
					$actual_basevalue = $rows_actual_ind_value["basevalue"];
				}

				$basevalue = $initial_basevalue + $actual_basevalue;

				$query_annual_target = $db->prepare("SELECT SUM(target) AS target FROM tbl_programs_based_budget WHERE indid=:indid AND finyear=:finyear");
				$query_annual_target->execute(array(":indid" => $indid, ":finyear" => $startyear));
				$rows_annual_target = $query_annual_target->fetch();
				$totalRows_annual_target = $query_annual_target->rowCount();

				if ($totalRows_annual_target > 0) {
					$annualtarget = $rows_annual_target["target"];
				}

				$query_quarterly_target = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE indid=:indid AND year=:finyear");
				$query_quarterly_target->execute(array(":indid" => $indid, ":finyear" => $startyear));
				$rows_quarterly_target = $query_quarterly_target->fetch();
				$totalRows_quarterly_target = $query_quarterly_target->rowCount();

				$query_quarter_one_actual = $db->prepare("SELECT SUM(actualoutput) AS achieved FROM tbl_monitoringoutput WHERE opid=:indid AND (date_created>=:startq1 AND date_created<=:endq1)");
				$query_quarter_one_actual->execute(array(":indid" => $indid, ":startq1" => $startq1, ":endq1" => $endq1));
				$rows_quarter_one_actual = $query_quarter_one_actual->fetch();
				$totalRows_quarter_one_actual = $query_quarter_one_actual->rowCount();
				if ($totalRows_quarter_one_actual > 0) {
					$quarter_one_achived = $rows_quarter_one_actual["achieved"];
				}

				$query_quarter_two_actual = $db->prepare("SELECT SUM(actualoutput) AS achieved FROM tbl_monitoringoutput WHERE opid=:indid AND (date_created>=:startq2 AND date_created<=:endq2)");
				$query_quarter_two_actual->execute(array(":indid" => $indid, ":startq2" => $startq2, ":endq2" => $endq2));
				$rows_quarter_two_actual = $query_quarter_two_actual->fetch();
				$totalRows_quarter_two_actual = $query_quarter_two_actual->rowCount();
				if ($totalRows_quarter_two_actual > 0) {
					$quarter_two_achived = $rows_quarter_two_actual["achieved"];
				}

				$query_quarter_three_actual = $db->prepare("SELECT SUM(actualoutput) AS achieved FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.opid WHERE indicator=:indid AND (date_created>=:startq3 AND date_created<=:endq3)");
				$query_quarter_three_actual->execute(array(":indid" => $indid, ":startq3" => $startq3, ":endq3" => $endq3));
				$rows_quarter_three_actual = $query_quarter_three_actual->fetch();
				$totalRows_quarter_three_actual = $query_quarter_three_actual->rowCount();
				if ($totalRows_quarter_three_actual > 0) {
					$quarter_three_achived = $rows_quarter_three_actual["achieved"];
				}

				$query_quarter_four_actual = $db->prepare("SELECT SUM(actualoutput) AS achieved FROM tbl_monitoringoutput WHERE opid=:indid AND (date_created>=:startq4 AND date_created<=:endq4)");
				$query_quarter_four_actual->execute(array(":indid" => $indid, ":startq4" => $startq4, ":endq4" => $endq4));
				$rows_quarter_four_actual = $query_quarter_four_actual->fetch();
				$totalRows_quarter_four_actual = $query_quarter_four_actual->rowCount();
				if ($totalRows_quarter_four_actual > 0) {
					$quarter_four_achived = $rows_quarter_four_actual["achieved"];
				}

				/* $query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicatorID' AND baseline=1 AND indicator_category='Output' ");
				$query_Indicator->execute();
				$row = $query_Indicator->fetch();
				$unit = $row['unit']; */

				if ($totalRows_quarterly_target > 0) {
					$quarter_one_target = $rows_quarterly_target["Q1"];
					$quarter_two_target = $rows_quarterly_target["Q2"];
					$quarter_three_target = $rows_quarterly_target["Q3"];
					$quarter_four_target = $rows_quarterly_target["Q4"];

					$quarter_1_rate = number_format((($quarter_one_achived / $quarter_one_target) * 100), 2);
					$quarter_one_rate = $quarter_1_rate . "%";

					$quarter_2_rate = number_format((($quarter_two_achived / $quarter_two_target) * 100), 2);
					$quarter_two_rate = $quarter_2_rate . "%";

					$quarter_3_rate = number_format((($quarter_three_achived / $quarter_three_target) * 100), 2);
					$quarter_three_rate = $quarter_3_rate . "%";

					$quarter_4_rate = number_format((($quarter_four_achived / $quarter_four_target) * 100), 2);
					$quarter_four_rate = $quarter_4_rate . "%";
				}
			}


            $body .= '
               <tr>
                  <td>' . $sn . '</td>
                  <td colspan="">' . $indicator . '</td>
                  <td colspan="">' . number_format($basevalue) . '</td>
                  <td colspan="">' . number_format($annualtarget) . '</td>
                  <td>' . number_format($quarter_one_target) . '</td>
                  <td>' . number_format($quarter_one_achived) . '</td>	
                  <td>' . $quarter_one_rate . '</td>	
                  <td>' . number_format($quarter_two_target) . '</td>	
                  <td>' . number_format($quarter_two_achived) . '</td>	
                  <td>' . $quarter_two_rate . '</td>	
                  <td>' . number_format($quarter_three_target) . '</td>	
                  <td>' . number_format($quarter_three_achived) . '</td>	
                  <td>' . $quarter_three_rate . '</td>	
                  <td>' . number_format($quarter_four_target) . '</td>	
                  <td>' . number_format($quarter_four_achived) . '</td>	
                  <td>' . $quarter_four_rate . '</td>
               </tr>';
        }
    }

    $body .= '
     </tbody>
   </table>';

    $mpdf->WriteHTML($body);
    $mpdf->WriteHTML('<h5 style="color:green">Printed By: '.$printedby.'</h5>');
    $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County');
    //$mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
    $mpdf->Output();
} catch (PDOException $ex) {
}
