<?php
 
//include_once 'projtrac-dashboard/resource/session.php';

include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php';


// $id = (isset($_GET['report'])) ? base64_decode($_GET['report']) : header("location:dashboard.php");

$id = 1;

try {
    $query_rsConclusion = $db->prepare("SELECT * FROM `tbl_capr_report_conclusion` WHERE id='$id'");
    $query_rsConclusion->execute();
    $Rows_rsConclusion = $query_rsConclusion->fetch();
    $totalRows_rsConclusion = $query_rsConclusion->rowCount();

    if ($totalRows_rsConclusion > 0) {
        $stid = $Rows_rsConclusion['stid'];
        $year = $Rows_rsConclusion['year'];
        $section_comments = $Rows_rsConclusion['section_comments'];
        $challenges = $Rows_rsConclusion['challenges'];
        $conclusion = $Rows_rsConclusion['conclusion'];
        $appendices = $Rows_rsConclusion['appendices'];

        $query_Sectors = $db->prepare("SELECT * FROM tbl_sectors WHERE stid='$stid'");
        $query_Sectors->execute();
        $totalRows_Sectors = $query_Sectors->rowCount();
        $Rows_Sectors = $query_Sectors->fetch();
        $sector = ($totalRows_Sectors > 0) ? $Rows_Sectors['sector'] : "";

        $query_sector = $db->prepare("SELECT indicator_sector,stid,sector FROM tbl_indicator i inner join tbl_sectors s on s.stid=i.indicator_sector WHERE i.indicator_category = 'Output' AND s.deleted='0' GROUP BY stid ORDER BY stid");
        $query_sector->execute();
        $totalRows_sector = $query_sector->rowCount();

        $query_years = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id='$year'");
        $query_years->execute();
        $totalRows_years = $query_years->rowCount();
        $Rows_years = $query_years->fetch();
        $fscyear = ($totalRows_years > 0) ? $Rows_years['yr'] : "";
        $end = $fscyear + 1;
        $financial_year = $fscyear . "/" . $end;
        $start = $fscyear - 1;
        $b_financial_year = $start . "/" . $fscyear;

        $query_ind = $db->prepare("SELECT indid, indicator_name, indicator_unit FROM tbl_indicator WHERE indicator_sector='$stid' AND indicator_category = 'Output' AND baseline=1");
        $query_ind->execute();
        $row_ind = $query_ind->fetch();
        $totalRows_ind = $query_ind->rowCount();

        $logo = 'logo.jpg';
        $stylesheet = file_get_contents('bootstrap.css');
        $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
        $mpdf->SetWatermarkImage($logo);
        $mpdf->showWatermarkImage = true;
        $mpdf->SetProtection(array(), 'UserPassword', 'password');

        $cover_page =
            '<div style="text-align: center;">
                <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
                <h2 style="" >COUNTY GOVERNMENT OF UASIN GISHU</h2>
                <br/>
                <hr/>
                <h3 style="margin-top:10px;" >' . $financial_year . ' ANNUAL PROGRESS REPORT</h3> 
                <hr/>
                <div style="margin-top:80px;" >
                    <address>
                        <h5>The County Treasury P. O. Box 40-30100 ELDORET, KENYA </h5>
                        <h5>Email: info@uasingishu.go.ke </h5>
                        <h5>Website: www.uasingishu.go.ke </h5>
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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Sector: '.$sector.'</h3>
                    '.$section_comments.'
                </div>';

                $table =
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr class="bg-light-blue">
                                    <th colspan="" rowspan="2" style="width:3%">#</th>
                                    <th colspan="" rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th colspan="" rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th colspan="" rowspan="2">Baseline&nbsp;&nbsp;(' . $b_financial_year . ')&nbsp;&nbsp;</th>
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

                                        $query_indRemarks =  $db->prepare("SELECT * FROM tbl_capr_report_remarks WHERE indid='$indid' AND year='$year'");
                                        $query_indRemarks->execute();
                                        $row_indRemarks = $query_indRemarks->fetch();
                                        $count_row_indRemarks = $query_indRemarks->rowCount();

                                        $query_rsProgramTargets =  $db->prepare("SELECT SUM(target) as target FROM `tbl_progdetails` WHERE indicator='$indid' AND year='$fscyear' ");
                                        $query_rsProgramTargets->execute();
                                        $row_rsProgramTargets = $query_rsProgramTargets->fetch();
                                        $count_row_rsProgramTargets = $query_rsProgramTargets->rowCount();
                                        $targets = ($count_row_rsProgramTargets > 0) ? $row_rsProgramTargets["target"] : '0';

                                        $query_indbaseline =  $db->prepare("SELECT SUM(v.value) as baseline FROM tbl_indicator_baseline_years s INNER JOIN tbl_indicator_output_baseline_values v ON s.indid = v.indid WHERE s.indid ='$indid'");
                                        $query_indbaseline->execute();
                                        $row_indbaseline = $query_indbaseline->fetch();
                                        $count_row_indbaseline = $query_indbaseline->rowCount();
                                        $basevalue = $row_indbaseline["baseline"] > 0 ?  $row_indbaseline["baseline"] : 0;

                                        $query_indBaselineBase =  $db->prepare("SELECT SUM(actualoutput) as baseline FROM tbl_monitoringoutput m INNER JOIN tbl_project_output_details d ON d.id =  m.opid INNER JOIN tbl_progdetails g ON g.id =  d.outputid WHERE date_created >= '$start-07-01' AND  date_created <= '$fscyear-06-30' AND g.indicator='$indid'");
                                        $query_indBaselineBase->execute();
                                        $row_indBaselineBase = $query_indBaselineBase->fetch();
                                        $achived_output_base = $row_indBaselineBase["baseline"] > 0 ?  $row_indBaselineBase["baseline"] : 0;
                                        $basevalue = $basevalue + $achived_output_base;

                                        $query_indBaseline =  $db->prepare("SELECT SUM(actualoutput) as baseline FROM tbl_monitoringoutput m INNER JOIN tbl_project_output_details d ON d.id =  m.opid INNER JOIN tbl_progdetails g ON g.id =  d.outputid WHERE date_created >= '$fscyear-07-01' AND  date_created <= '$end-06-30' AND g.indicator='$indid'");
                                        $query_indBaseline->execute();
                                        $row_indBaseline = $query_indBaseline->fetch();
                                        $achived_output = $row_indBaseline["baseline"] > 0 ?  $row_indBaseline["baseline"] : 0;

                                        $rate = ($targets > 0) ? ($achived_output / $targets) * 100 : 0;
                                        $comments =  ($count_row_indRemarks > 0) ?  $row_indRemarks['remarks'] : "";
                                        $table .= '
                                                        <tr>
                                                            <td>' . $i . '</td>
                                                            <td>' . ucfirst($indicator) . '</td>
                                                            <td>' . ucfirst($unit) . " of " . $indicator . " " . $indid . '</td>
                                                            <td>' . number_format($basevalue) . '</td>
                                                            <td>' . number_format($targets) . '</td>
                                                            <td>' . number_format(($achived_output)) . '</td>
                                                            <td>' . number_format($rate, 2) . ' </td>
                                                            <td> 
                                                                ' . $comments . ' 
                                                            </td>
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
                    <h3>Challenges and Recommendations: </h3>
                    '.$challenges.'
                </div>';

                $conc =
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Lessons learnt and Conclusion: </h3>
                    '.$conclusion.'
                </div>';

                $append =
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Appendices for additional documents/materials: </h3>
                    '.$appendices.'
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


        $mpdf->WriteHTML('<h4 style="color:green">Printed By: </h4>');
        $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
        // $mpdf->Output("Output.pdf", "D");
        $mpdf->Output();
    } else {
        header('location: dashboard.php');
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
}
