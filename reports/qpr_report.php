<?php



//include_once 'projtrac-dashboard/resource/session.php';
session_start();
$user_name = $_SESSION['MM_Username'];

include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php';

if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {

    try {
        $financial_year_id = $b_financial_year = '';
        $sector_id = $_GET['sector'];
        $department_id = isset($_GET['department']) ? $_GET['department'] : '';
        $financial_year_id = $_GET['fyid'];
        $quarter = $_GET['quarter'];

        var_dump($financial_year_id);


        $query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
        $query_company->execute();
        $row_company = $query_company->fetch();

        $query_user =  $db->prepare("SELECT p.*, u.password as password FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid =:user_id");
        $query_user->execute(array(":user_id" => $user_name));
        $row_rsUser = $query_user->fetch();
        $printedby = $row_rsUser["title"] . "." . $row_rsUser["fullname"];

        $query_rsConclusion = $db->prepare("SELECT * FROM `tbl_qapr_report_conclusion` WHERE id='$id'");
        $query_rsConclusion->execute();
        $Rows_rsConclusion = $query_rsConclusion->fetch();
        $totalRows_rsConclusion = $query_rsConclusion->rowCount();

        $year = '';
        $section_comments = $challenges = $conclusion = $appendices = 'No record';
        if ($totalRows_rsConclusion > 0) {
            $section_comments = $Rows_rsConclusion['section_comments'];
            $challenges = $Rows_rsConclusion['challenges'];
            $conclusion = $Rows_rsConclusion['conclusion'];
            $appendices = $Rows_rsConclusion['appendices'];
        }

        $query_Sectors = $db->prepare("SELECT * FROM tbl_sectors WHERE stid=:sector_id");
        $query_Sectors->execute(array(":sector_id" => $sector_id));
        $totalRows_Sectors = $query_Sectors->rowCount();
        $Rows_Sectors = $query_Sectors->fetch();
        $sector = ($totalRows_Sectors > 0) ? $Rows_Sectors['sector'] : "";

        $query_years = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id=:financial_year_id");
        $query_years->execute(array(":financial_year_id" => $financial_year_id));
        $totalRows_years = $query_years->rowCount();
        $Rows_years = $query_years->fetch();
        $fscyear = ($totalRows_years > 0) ? $Rows_years['yr'] : "";
        $end = $fscyear + 1;
        $financial_year = $fscyear . "/" . $end;
        $start = $fscyear - 1;
        $b_financial_year = $start . "/" . $fscyear;

        var_dump($financial_year_id);

        $basedate = $fscyear . "-06-30";
        $start_date = $end_date = "";
        if ($quarter == 1) {
            $start_date = $fscyear . "-07-01";
            $end_date = $fscyear . "-09-30";
        } else if ($quarter == 2) {
            $start_date = $fscyear . "-10-01";
            $end_date = $fscyear . "-12-31";
        } else if ($quarter == 3) {
            $start_date = $endyear . "-01-01";
            $end_date = $endyear . "-03-31";
        } else if ($quarter == 4) {
            $start_date = $endyear . "-04-01";
            $end_date = $endyear . "-06-30";
        }

        $financial_year_date = "Q$quarter ( " . $start_date . " to " . $end_date . ")";

        $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
        $query_indicators->execute(array(":sector" => $sector_id));
        $row_indicators = $query_indicators->fetch();
        $totalRows_indicators = $query_indicators->rowCount();

        $logo = 'logo.jpg';
        $stylesheet = file_get_contents('bootstrap.css');
        $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
        $mpdf->SetWatermarkImage($logo);
        $mpdf->showWatermarkImage = true;
        // $mpdf->SetProtection(array(), 'UserPassword', 'password');

        $cover_page =
            '<div style="text-align: center;">
                <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
                <h2 style="" >' . $row_company["company_name"] . '</h2>
                <br/>
                <hr/>
                <h3 style="margin-top:10px;" >' . $financial_year . ' ANNUAL PROGRESS REPORT</h3>
                <hr/>
                <div style="margin-top:80px;" >
                    <address>
                        <h5>The County Treasury ' . $row_company["postal_address"] . ', KENYA </h5>
                        <h5>Email: ' . $row_company["email_address"] . ' </h5>
                        <h5>Website: ' . $row_company["domain_address"] . ' </h5>
                    </address>
                </div>
            </div>';

        $header =
            '<div style="text-align: right;">
                <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
                <p> <i><small>' . $financial_year . ' ANNUAL PROGRESS REPORT</small></i></p>
            </div>';


        $sect =
            '<div class="row clearfix">
            <h4>Chapter 1: Performance</h4>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h5> 1.1 Mandate and overall goals </h5>
                    ' . $section_comments . '
                </div>';

        $table =
            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h5>1.2 Indicators Performance</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr class="bg-light-blue">
                                    <th colspan="" rowspan="2" style="width:3%">#</th>
                                    <th colspan="" rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th colspan="" rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th colspan="" rowspan="2">Baseline&nbsp;&nbsp;(' . $b_financial_year . ')&nbsp;&nbsp;</th>
                                    <th colspan="" rowspan="2">Target at end Of&nbsp;&nbsp;(' . $b_financial_year . ')&nbsp;&nbsp;</th>
                                    <th colspan="3" rowspan="">' . $financial_year_date . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th colspan="" rowspan="2">Comments&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                                <tr class="bg-light-blue">
                                    <th>Target</th>
                                    <th>Achieved</th>
                                    <th>Rate (%)</th>
                                </tr>
                            </thead>
                            <tbody>';

        if ($totalRows_indicators > 0) {
            $counter = 0;
            do {
                $counter++;
                $initial_basevalue = 0;
                $actual_basevalue = 0;
                $annualtarget = 0;
                $basevalue = 0;
                $indid = $row_indicators['indid'];
                $indicator = $row_indicators['indicator_name'];
                $unit_id = $row_indicators['indicator_unit'];

                $query_indbasevalue_year = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid AND year <= :finyear");
                $query_indbasevalue_year->execute(array(":indid" => $indid, ":finyear" => $start));
                $totalRows_indbasevalue_year = $query_indbasevalue_year->rowCount();

                if ($totalRows_indbasevalue_year >= 0) {
                    $query_initial_indbasevalue = $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid=:indid ");
                    $query_initial_indbasevalue->execute(array(":indid" => $indid));
                    $rows_initial_indbasevalue = $query_initial_indbasevalue->fetch();
                    $totalRows_initial_indbasevalue = $query_initial_indbasevalue->rowCount();

                    if ($totalRows_initial_indbasevalue > 0) {
                        $initial_basevalue = $rows_initial_indbasevalue["basevalue"];
                    }

                    $query_actual_ind_value = $db->prepare("SELECT SUM(achieved) AS basevalue FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE d.indicator=:indid AND m.date_created < '$basedate'");
                    $query_actual_ind_value->execute(array(":indid" => $indid));
                    $rows_actual_ind_value = $query_actual_ind_value->fetch();
                    $totalRows_actual_indbasevalue = $query_actual_ind_value->rowCount();

                    if ($totalRows_actual_indbasevalue > 0) {
                        $actual_basevalue = $rows_actual_ind_value["basevalue"];
                    }

                    $basevalue = $initial_basevalue + $actual_basevalue;

                    $query_annual_target = $db->prepare("SELECT SUM(target) AS target FROM tbl_programs_based_budget WHERE indid=:indid AND finyear=:finyear");
                    $query_annual_target->execute(array(":indid" => $indid, ":finyear" => $fscyear));
                    $rows_annual_target = $query_annual_target->fetch();
                    $totalRows_annual_target = $query_annual_target->rowCount();

                    if ($totalRows_annual_target > 0) {
                        $annualtarget = !is_null($rows_annual_target["target"]) ? $rows_annual_target["target"] : 0;
                    }

                    $query_quarterly_target = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE indid=:indid AND year=:finyear");
                    $query_quarterly_target->execute(array(":indid" => $indid, ":finyear" => $fscyear));
                    $rows_quarterly_target = $query_quarterly_target->fetch();
                    $totalRows_quarterly_target = $query_quarterly_target->rowCount();

                    $query_quarter_one_actual = $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE indicator=:indid AND (date_created>=:startq1 AND date_created<=:endq1)");
                    $query_quarter_one_actual->execute(array(":indid" => $indid, ":startq1" => $start_date, ":endq1" => $end_date));
                    $rows_quarter_one_actual = $query_quarter_one_actual->fetch();
                    $totalRows_quarter_one_actual = $query_quarter_one_actual->rowCount();
                    $quarter_achived =  !is_null($rows_quarter_one_actual["achieved"]) ? $rows_quarter_one_actual["achieved"] : 0;


                    $query_Indicator = $db->prepare("SELECT * FROM tbl_measurement_units  WHERE id=:unit ");
                    $query_Indicator->execute(array(":unit" => $unit_id));
                    $row = $query_Indicator->fetch();
                    $count_meas = $query_Indicator->rowCount();
                    $unit = $count_meas > 0 ? $row['unit'] : "";

                    $quarter_rate = "";
                    if ($totalRows_quarterly_target > 0) {
                        $quarter_target =  $rows_quarterly_target["Q$quarter"];
                        $quarter_1_rate = number_format((($quarter_achived / $quarter_target) * 100), 2);
                        $quarter_rate = $quarter_1_rate . "%";
                    }
                }


                $query_indRemarks =  $db->prepare("SELECT * FROM tbl_qapr_report_remarks WHERE indid='$indid' AND year='$year' AND quarter='$quarter'");
                $query_indRemarks->execute();
                $row_indRemarks = $query_indRemarks->fetch();
                $count_row_indRemarks = $query_indRemarks->rowCount();
                $remarks = $count_row_indRemarks > 0 ? $row_indRemarks['remarks'] : 'No record';

                $table .=
                    '<tr>
                        <td>' . $counter . '</td>
                        <td colspan="">' .  $indicator . '</td>
                        <td colspan="">' . $unit . ' of ' . $indicator . '</td>
                        <td colspan="">' . number_format($basevalue, 2) . '</td>
                        <td colspan="">' . number_format($annualtarget, 2) . '</td>
                        <td>' . number_format($quarter_target, 2) . '</td>
                        <td>' . number_format($quarter_achived, 2) . '</td>
                        <td>' . number_format($quarter_rate, 2) . '</td>
                        <td>' . $remarks . ' </td>
                    </tr>';
            } while ($row_indicators = $query_indicators->fetch());
        }
        $table .= '
                            </tbody>
                        </table>
                    </div>
                </div>';

        $challenge =
            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4>Chapter 2: Challenges and Recommendations: </h4>
                    ' . $challenges . '
                </div>';

        $conc =
            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4>Chapter 3: Lessons learnt and Conclusion: </h4>
                    ' . $conclusion . '
                </div>';

        $append =
            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4>Appendices for additional documents/materials: </h4>
                    ' . $appendices . '
                </div>
            </div>';

        $mpdf->AddPage('p');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($cover_page, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->SetHTMLHeader($header);

        $mpdf->AddPage('p');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($sect, \Mpdf\HTMLParserMode::HTML_BODY);

        $mpdf->AddPage('l');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($table, \Mpdf\HTMLParserMode::HTML_BODY);

        $mpdf->AddPage('p');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($challenge, \Mpdf\HTMLParserMode::HTML_BODY);

        $mpdf->AddPage('p');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($conc, \Mpdf\HTMLParserMode::HTML_BODY);

        $mpdf->AddPage('p');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($append, \Mpdf\HTMLParserMode::HTML_BODY);


        $mpdf->WriteHTML('<h5 style="color:green">Printed By: ' . $printedby . '</h5>');
        $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
        // $mpdf->Output("Output.pdf", "D");
        $mpdf->Output();
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
        echo $result;
    }
} else {
    header("location:../dashboard.php");
}
