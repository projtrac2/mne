<?php
session_start();
$user_name = $_SESSION['MM_Username'];
include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php';


if (isset($_GET['fyid'])) {
    $sector_id = $_GET['department'];
    $financial_year_id = $_GET['fyid'];


    try {

        $query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
        $query_company->execute();
        $row_company = $query_company->fetch();

        $query_logged_in_user =  $db->prepare("SELECT title, fullname FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid=:user_name");
        $query_logged_in_user->execute(array(":user_name" => $user_name));
        $row_user = $query_logged_in_user->fetch();
        $printedby = $row_user["title"] . "." . $row_user["fullname"];

        $query_rsConclusion = $db->prepare("SELECT * FROM `tbl_capr_report_conclusion` WHERE year=:year");
        $query_rsConclusion->execute(array(":year" => $financial_year_id));
        $Rows_rsConclusion = $query_rsConclusion->fetch();
        $totalRows_rsConclusion = $query_rsConclusion->rowCount();

        $section_comments = $challenges = $conclusion =  $appendices = "No record";

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
        $fscend = $fscyear + 1;
        $financial_year = $fscyear . "/" . $fscend;

        $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 LIMIT 1");
        $query_rsStrategicPlan->execute();
        $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
        $stp_start_year = $row_rsStrategicPlan['starting_year'];
        $stp_duration = $row_rsStrategicPlan['years'];
        $stp_end_year = $stp_start_year + $stp_duration - 1;

        $query_ind = $db->prepare("SELECT indid, indicator_name, indicator_unit FROM tbl_indicator WHERE indicator_sector=:sector_id AND indicator_category = 'Output' AND baseline=1");
        $query_ind->execute(array(":sector_id" => $sector_id));
        $row_ind = $query_ind->fetch();
        $totalRows_ind = $query_ind->rowCount();

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
                                    <th colspan="" rowspan="2">Baseline</th>
									<th colspan="" rowspan="2">Target at end of the CIDP period &nbsp;&nbsp;(' . $stp_end_year . ')&nbsp;&nbsp;</th>
                                    <th colspan="3" rowspan="">' . $financial_year . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th colspan="" rowspan="2">Comments&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                                <tr class="bg-light-blue">
                                    <th>Target</th>
                                    <th>Achieved</th>
                                    <th>Rate (%)</th>
                                </tr>
                            </thead>
                            <tbody>';
        if ($totalRows_ind > 0) {
            $i = 0;
            do {
                $i++;
                $indicator = $row_ind["indicator_name"];
                $unitid = $row_ind["indicator_unit"];
                $indid = $row_ind["indid"];
                $query_indunit =  $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$unitid'");
                $query_indunit->execute();
                $row_indunit = $query_indunit->fetch();
                $unit = ($row_indunit) ? $row_indunit["unit"] : "";

                $query_rscidpTargets =  $db->prepare("SELECT SUM(target) AS target FROM `tbl_progdetails` WHERE year >= $stp_start_year and year <= $stp_end_year AND indicator = $indid");
                $query_rscidpTargets->execute();
                $row_rscidpTargets = $query_rscidpTargets->fetch();
                $count_row_rscidpTargets = $query_rscidpTargets->rowCount();
                $cidp_target = ($count_row_rscidpTargets > 0) ? $row_rscidpTargets["target"] : '0';

                $query_indRemarks =  $db->prepare("SELECT * FROM tbl_capr_report_remarks WHERE indid='$indid' AND year='$year'");
                $query_indRemarks->execute();
                $row_indRemarks = $query_indRemarks->fetch();
                $count_row_indRemarks = $query_indRemarks->rowCount();

                $query_rsProgramTargets =  $db->prepare("SELECT SUM(target) as target FROM `tbl_progdetails` WHERE indicator='$indid' AND year='$fscyear' ");
                $query_rsProgramTargets->execute();
                $row_rsProgramTargets = $query_rsProgramTargets->fetch();
                $count_row_rsProgramTargets = $query_rsProgramTargets->rowCount();
                $targets = ($count_row_rsProgramTargets > 0) ? $row_rsProgramTargets["target"] : '0';

                $query_indbaseline =  $db->prepare("SELECT SUM(value) as baseline FROM tbl_indicator_output_baseline_values WHERE indid ='$indid'");
                $query_indbaseline->execute();
                $row_indbaseline = $query_indbaseline->fetch();
                $count_row_indbaseline = $query_indbaseline->rowCount();
                $basevalue = $row_indbaseline["baseline"] > 0 ?  $row_indbaseline["baseline"] : 0;

                $enddate = $fscyear . "-06-30";
                $query_indBaselineBase =  $db->prepare("SELECT SUM(actualoutput) as achieved FROM tbl_monitoringoutput m INNER JOIN tbl_project_details d ON d.id =  m.opid INNER JOIN tbl_progdetails g ON g.id =  d.outputid WHERE date_created <= '" . $enddate . "' AND g.indicator='$indid'");
                $query_indBaselineBase->execute();
                $row_indBaselineBase = $query_indBaselineBase->fetch();
                $achived_output_base = !is_null($row_indBaselineBase["achieved"])  ?  $row_indBaselineBase["achieved"] : 0;
                $basevalue = $basevalue + $achived_output_base;

                $achievedstartdate = $fscyear . "-07-01";
                $achievedenddate = $fscend . "-06-30";

                $query_indachieved =  $db->prepare("SELECT SUM(actualoutput) as achieved FROM tbl_monitoringoutput m INNER JOIN tbl_project_details d ON d.id =  m.opid INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE g.indicator='$indid' AND (date_created >= '" . $achievedstartdate . "' AND  date_created <= '" . $achievedenddate . "')");
                $query_indachieved->execute();
                $row_indachieved = $query_indachieved->fetch();
                $achived_output = $row_indachieved["achieved"] > 0 ?  $row_indachieved["achieved"] : 0;

                $rate = ($targets > 0) ? ($achived_output / $targets) * 100 : 0;
                $comments =  ($count_row_indRemarks > 0) ?  $row_indRemarks['remarks'] : "No record";
                $table .= '
                <tr>
                    <td>' . $i . '</td>
                    <td>' . ucfirst($indicator) . '</td>
                    <td>' . ucfirst($unit) . " of " . $indicator . '</td>
                    <td>' . number_format($basevalue, 2) . '</td>
                    <td>' . number_format($cidp_target, 2) . '</td>
                    <td>' . number_format($targets, 2) . '</td>
                    <td>' . number_format($achived_output, 2) . '</td>
                    <td>' . number_format($rate, 2) . ' </td>
                    <td>' . $comments . '</td>
                </tr>';
            } while ($row_ind = $query_ind->fetch());
        } else {
            $table .= "<tr><td colspan='8'>No Record Found</td></tr>";
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
        $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County');
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
