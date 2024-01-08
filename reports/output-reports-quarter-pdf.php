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
    function get_achieved($indid, $start_date, $end_date)
    {
        global $db;
        $query_quarter_four_actual = $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput m
		inner join tbl_project_details d on d.id=m.output_id WHERE indicator=:indid AND (date_created>=:start_date AND date_created<=:end_date)AND record_type=2");
        $query_quarter_four_actual->execute(array(":indid" => $indid, ":start_date" => $start_date, ":end_date" => $end_date));
        $rows_quarter_four_actual = $query_quarter_four_actual->fetch();
        return !is_null($rows_quarter_four_actual["achieved"]) ? $rows_quarter_four_actual["achieved"] : 0;
    }
    $query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
    $query_company->execute();
    $row_company = $query_company->fetch();

    $logo = 'logo.jpg';
    $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
    $mpdf->SetWatermarkImage($logo);
    $mpdf->showWatermarkImage = true;
    $mpdf->SetProtection(array(), 'UserPassword', 'password');

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



    $yr = date("Y");
    $mnth = date("m");
    $startmnth = 07;
    $endmnth = 06;

    if ($mnth >= 7 && $mnth <= 12) {
        $startyear = $yr;
        $endyear = $yr + 1;
    } elseif ($mnth >= 1 && $mnth <= 6) {
        $startyear = $yr - 1;
        $endyear = $yr;
    }

    $base_url = "";

    //$quarter_dates_arr = ["-07-01", "-09-30", "-10-01", "-12-31", "-01-01", "-03-30", "-04-01","-06-30"];

    $query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where yr =:year ");
    $query_rsFscYear->execute(array(":year" => $startyear));
    $row_rsFscYear = $query_rsFscYear->fetch();

    $fyid = $row_rsFscYear['id'];

    if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
        $sector = isset($_GET['sector']) ? $_GET['sector'] : '';
        $dept = isset($_GET['department']) ? $_GET['department'] : '';
        $fyid = isset($_GET['indfy']) ? $_GET['indfy'] : '';

        if (!empty($sector) && !empty($fyid) && empty($dept)) {
            $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
            $query_indicators->execute(array(":sector" => $sector));

            $query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id =:year ");
            $query_rsFscYear->execute(array(":year" => $fyid));
            $row_rsFscYear = $query_rsFscYear->fetch();
            $startyear = $row_rsFscYear['yr'];
            $endyear = $startyear + 1;

            $base_url = "indfy=$fyid&sector=$sector";
        }

        if (!empty($sector) && empty($dept) && empty($fyid)) {
            $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
            $query_indicators->execute(array(":sector" => $sector));
            $base_url = "sector=$sector";
        } elseif (!empty($sector) && !empty($dept) && empty($fyid)) {
            $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector and indicator_dept=:dept ORDER BY `indid` ASC");
            $query_indicators->execute(array(":sector" => $sector, ":dept" => $dept));
            $base_url = "dept=$dept&sector=$sector";
        } elseif (!empty($fyid) && empty($sector) && empty($dept)) {
            $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
            $query_indicators->execute();

            $query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id =:year ");
            $query_rsFscYear->execute(array(":year" => $fyid));
            $row_rsFscYear = $query_rsFscYear->fetch();
            $startyear = $row_rsFscYear['yr'];
            $endyear = $startyear + 1;
            $base_url = "indfy=$fyid";
        } elseif (empty($_GET['indfy']) && empty($sector) && empty($dept)) {
            $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
            $query_indicators->execute();
        } elseif (!empty($sector) && !empty($dept) && !empty($fyid)) {
            $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector and indicator_dept=:dept ORDER BY `indid` ASC");
            $query_indicators->execute(array(":sector" => $sector, ":dept" => $dept));
            $base_url = "indfy=$fyid&dept=$dept&sector=$sector";
        }
    } else {
        $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1");
        $query_indicators->execute();
    }

    $financialyear = $startyear . "/" . $endyear;
    $totalRows_indicators = $query_indicators->rowCount();

    $basedate = $startyear . "-06-30";
    $startq1 = $startyear . "-07-01";
    $endq1 = $startyear . "-09-30";
    $startq2 = $startyear . "-10-01";
    $endq2 = $startyear . "-12-31";
    $startq3 = $endyear . "-01-01";
    $endq3 = $endyear . "-03-31";
    $startq4 = $endyear . "-04-01";
    $endq4 = $endyear . "-06-30";

    $filter = '
	<table class="table table-bordered table-striped table-hover dataTable" id="qapr_reports">
		<thead>
			<tr class="bg-light-blue">
				<th colspan="" rowspan="2">#</th>
				<th colspan="" rowspan="2">Indicator </th>
				<th colspan="" rowspan="2">Baseline</th>
				<th colspan="" rowspan="2">Annual Target</th>
				<th colspan="3"> Q1 (' . $startq1 . ' to ' . $endq1 . ')</th>
				<th colspan="3"> Q2 (' . $startq2 . ' to ' . $endq2 . ')</th>
				<th colspan="3"> Q3 (' . $startq3 . ' to ' . $endq3 . ')</th>
				<th colspan="3"> Q4 (' . $startq4 . ' to ' . $endq4 . ')</th>
				</tr>
				<tr class="bg-light-blue">';
    for ($jp = 0; $jp < 4; $jp++) {
        $filter .= '
        <th>Target</th>
        <th>Achieved</th>
        <th>Rate (%)</th>';
    }
    $filter .= '
				</tr>
		</thead>
		<tbody>';
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
            $indid = $row_indicators['indid'];
            $indicator = $row_indicators['indicator_name'];

            $query_indbasevalue_year = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid");
            $query_indbasevalue_year->execute(array(":indid" => $indid));
            $totalRows_indbasevalue_year = $query_indbasevalue_year->rowCount();

            if ($totalRows_indbasevalue_year > 0) {
                $sn++;
                $query_initial_indbasevalue = $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid=:indid ");
                $query_initial_indbasevalue->execute(array(":indid" => $indid));
                $rows_initial_indbasevalue = $query_initial_indbasevalue->fetch();
                $initial_basevalue = !is_null($rows_initial_indbasevalue["basevalue"]) ?  $rows_initial_indbasevalue["basevalue"] : 0;

                $query_actual_ind_value = $db->prepare("SELECT SUM(achieved) AS basevalue FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE d.indicator=:indid AND m.date_created < '$basedate' AND record_type=2");
                $query_actual_ind_value->execute(array(":indid" => $indid));
                $rows_actual_ind_value = $query_actual_ind_value->fetch();
                $actual_basevalue = !is_null($rows_actual_ind_value["basevalue"]) ? $rows_actual_ind_value["basevalue"] : 0;


                $basevalue = $initial_basevalue + $actual_basevalue;

                $query_annual_target = $db->prepare("SELECT SUM(target) AS target FROM tbl_programs_based_budget WHERE indid=:indid AND finyear=:finyear");
                $query_annual_target->execute(array(":indid" => $indid, ":finyear" => $startyear));
                $rows_annual_target = $query_annual_target->fetch();
                $annualtarget = !is_null($rows_annual_target["target"]) ? $rows_annual_target["target"] : 0;


                $query_quarterly_target = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE indid=:indid AND year=:finyear");
                $query_quarterly_target->execute(array(":indid" => $indid, ":finyear" => $startyear));
                $rows_quarterly_target = $query_quarterly_target->fetch();
                $totalRows_quarterly_target = $query_quarterly_target->rowCount();

                $quarter_one_achived = get_achieved($indid, $startq1, $endq1);
                $quarter_two_achived = get_achieved($indid, $startq2, $endq2);
                $quarter_three_achived = get_achieved($indid, $startq3, $endq3);
                $quarter_four_achived = get_achieved($indid, $startq4, $endq4);
                $quarter_one_rate = 0 . "%";
                $quarter_two_rate = 0 . "%";
                $quarter_three_rate = 0 . "%";
                $quarter_four_rate = 0 . "%";

                if ($totalRows_quarterly_target > 0) {

                    $quarter_one_target = $rows_quarterly_target["Q1"];
                    $quarter_two_target = $rows_quarterly_target["Q2"];
                    $quarter_three_target = $rows_quarterly_target["Q3"];
                    $quarter_four_target = $rows_quarterly_target["Q4"];

                    if ($quarter_one_target > 0 && $quarter_one_achived > 0) {
                        $quarter_1_rate = number_format((($quarter_one_achived / $quarter_one_target) * 100), 2);
                        $quarter_one_rate = $quarter_1_rate . "%";
                    }

                    if ($quarter_two_target > 0 && $quarter_two_achived > 0) {
                        $quarter_2_rate = number_format((($quarter_two_achived / $quarter_two_target) * 100), 2);
                        $quarter_two_rate = $quarter_2_rate . "%";
                    }

                    if ($quarter_three_target > 0 && $quarter_three_achived > 0) {
                        $quarter_3_rate = number_format((($quarter_three_achived / $quarter_three_target) * 100), 2);
                        $quarter_three_rate = $quarter_3_rate . "%";
                    }

                    if ($quarter_four_target > 0 && $quarter_four_achived > 0) {
                        $quarter_4_rate = number_format((($quarter_four_achived / $quarter_four_target) * 100), 2);
                        $quarter_four_rate = $quarter_4_rate . "%";
                    }
                }

                $filter .= '
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
    } else {
        $filter .= '
        <tr>
            <td colspan="16" style="color:red">
                <h4>No record found!</h4>
            </td>
        </tr>';
    }
    $filter .= '
		</tbody>
	</table>';

    $query_user =  $db->prepare("SELECT p.*, u.password as password FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid =:user_id");
    $query_user->execute(array(":user_id" => $user_name));
    $row_rsUser = $query_user->fetch();
    $printedby = $row_rsUser["title"] . "." . $row_rsUser["fullname"];

    $stylesheet = file_get_contents('bootstrap.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($filter, \Mpdf\HTMLParserMode::HTML_BODY);
    //  $mpdf->WriteHTML($body);
    $mpdf->WriteHTML('<h5 style="color:green">Printed By: ' . $printedby . '</h5>');
    $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
    $mpdf->Output();
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
}
